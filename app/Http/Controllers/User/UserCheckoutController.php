<?php

namespace App\Http\Controllers\User;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UserCheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function process(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'total_harga' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
        ]);

        $cart = json_decode($request->cart, true);
        $totalHarga = $request->total_harga;

        try {
            $pesanan = DB::transaction(function () use ($request, $cart, $totalHarga) {
                $lastOrder = Pesanan::orderBy('id', 'desc')->lockForUpdate()->first();
                $lastOrderNumber = $lastOrder ? intval(substr($lastOrder->kode_pemesanan, -4)) : 0;
                $newOrderNumber = str_pad($lastOrderNumber + 1, 4, '0', STR_PAD_LEFT);
                $kodePemesanan = 'ORDPesanan-' . $newOrderNumber;

                $pesanan = new Pesanan();
                $pesanan->user_id = auth()->id();
                $pesanan->kode_pemesanan = $kodePemesanan;
                $pesanan->total_harga = $totalHarga;
                $pesanan->latitude = $request->latitude;
                $pesanan->longitude = $request->longitude;
                $pesanan->status = 'diproses';
                $pesanan->waktu_diproses = now();
                $pesanan->save();

                foreach ($cart as $item) {
                    $detailPesanan = new DetailPesanan();
                    $detailPesanan->pesanan_id = $pesanan->id;
                    $detailPesanan->jenis_menu_id = $item['id'];
                    $detailPesanan->jumlah = $item['quantity'];
                    $detailPesanan->harga = $item['harga'];
                    $detailPesanan->subtotal = $item['harga'] * $item['quantity'];
                    $detailPesanan->save();
                }

                return $pesanan;
            });

            $user = auth()->user();

            $itemDetails = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'price' => $item['harga'],
                    'quantity' => $item['quantity'],
                    'name' => $item['varian'] ?? 'Menu Item'
                ];
            }, $cart);

            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $request->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost'
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $pesanan->kode_pemesanan,
                    'gross_amount' => $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone_number ?? '',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $pesanan->kode_pemesanan
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout process error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            $pesanan = Pesanan::where('kode_pemesanan', $request->order_id)->first();
            if ($pesanan) {
                $pesanan->status = $this->mapPaymentStatus($request->transaction_status);
                if ($pesanan->status == 'dibayar') {
                    $pesanan->waktu_dibayar = now();
                } elseif ($pesanan->status == 'dibatalkan') {
                    $pesanan->waktu_dibatalkan = now();
                }
                $pesanan->metode_pembayaran = $this->getPaymentMethod($request->payment_type);
                $pesanan->id_transaksi_midtrans = $request->transaction_id;
                $pesanan->save();

                Log::info('Payment notification received', [
                    'order_id' => $request->order_id,
                    'status' => $request->transaction_status
                ]);

                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false], 403);
    }


    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:pesanan,kode_pemesanan',
        ]);

        try {
            $pesanan = Pesanan::where('kode_pemesanan', $request->order_id)->firstOrFail();

            $midtransStatus = \Midtrans\Transaction::status($request->order_id);

            $transactionStatus = is_array($midtransStatus)
                ? $midtransStatus['transaction_status']
                : (is_object($midtransStatus) ? $midtransStatus->transaction_status : null);

            $paymentType = is_array($midtransStatus)
                ? ($midtransStatus['payment_type'] ?? null)
                : (is_object($midtransStatus) ? $midtransStatus->payment_type : null);

            $transactionId = is_array($midtransStatus)
                ? ($midtransStatus['transaction_id'] ?? null)
                : (is_object($midtransStatus) ? $midtransStatus->transaction_id : null);

            if ($transactionStatus !== null) {
                $newStatus = $this->mapPaymentStatus($transactionStatus);
                if ($pesanan->status !== $newStatus) {
                    $pesanan->status = $newStatus;
                    $pesanan->waktu_dibayar = $newStatus === 'dibayar' ? now() : null;
                    $pesanan->metode_pembayaran = $paymentType ? $this->getPaymentMethod($paymentType) : null;
                    $pesanan->id_transaksi_midtrans = $transactionId;
                    $pesanan->save();
                }
            }

            return response()->json([
                'success' => true,
                'order_status' => $pesanan->status,
                'midtrans_status' => $transactionStatus,
                'payment_method' => $pesanan->metode_pembayaran ?? 'Belum dibayar',
                'message' => $this->getStatusMessage($pesanan->status)
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function mapPaymentStatus($midtransStatus)
    {
        switch ($midtransStatus) {
            case 'capture':
            case 'settlement':
                return 'dibayar';
            case 'pending':
                return 'diproses';
            case 'deny':
            case 'cancel':
            case 'expire':
                return 'dibatalkan';
            default:
                return 'diproses';
        }
    }

    private function getPaymentMethod($type, $bankName = null)
    {
        switch ($type) {
            case 'credit_card':
                return 'Kartu Kredit';
            case 'bca_klikbca':
            case 'bca_klikpay':
                return 'BCA KlikPay';
            case 'bri_epay':
                return 'BRI e-Pay';
            case 'echannel':
                return 'Mandiri Bill Payment';
            case 'mandiri_clickpay':
                return 'Mandiri Clickpay';
            case 'cimb_clicks':
                return 'CIMB Clicks';
            case 'danamon_online':
                return 'Danamon Online Banking';
            case 'bank_transfer':
                if ($bankName) {
                    return strtoupper($bankName) . ' Virtual Account';
                }
                return 'Transfer Bank';
            case 'gopay':
                return 'GoPay';
            case 'indomaret':
                return 'Indomaret';
            case 'alfamart':
                return 'Alfamart';
            case 'akulaku':
                return 'Akulaku';
            case 'shopeepay':
                return 'ShopeePay';
            case 'qris':
                return 'QRIS';
            default:
                return 'Metode Lainnya';
        }
    }

    private function getStatusMessage($status)
    {
        switch ($status) {
            case 'dibayar':
                return 'Pembayaran berhasil. Pesanan Anda sedang diproses.';
            case 'diproses':
                return 'Pesanan Anda sedang diproses. Silakan selesaikan pembayaran jika belum.';
            case 'diantar':
                return 'Pesanan Anda sedang dalam perjalanan.';
            case 'selesai':
                return 'Pesanan Anda telah selesai. Terima kasih!';
            case 'dibatalkan':
                return 'Pesanan Anda telah dibatalkan.';
            default:
                return 'Status pesanan: ' . $status;
        }
    }
}