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
                'merchant_id' => config('midtrans.merchant_id'),
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'redirect_url' => Snap::createTransaction($params)->redirect_url
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout process error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        Log::info('Payment Notification Received', $request->all());

        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            Log::info('Signature key matched.');

            $pesanan = Pesanan::where('kode_pemesanan', $request->order_id)->first();

            if ($pesanan) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $pesanan->status = 'dibayar';
                    $pesanan->waktu_dibayar = now();
                    $pesanan->metode_pembayaran = $this->getPaymentMethod($request->payment_type);
                    $pesanan->id_transaksi_midtrans = $request->transaction_id;
                } elseif ($request->transaction_status == 'pending') {
                    $pesanan->status = 'menunggu pembayaran';
                } elseif (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                    $pesanan->status = 'dibatalkan';
                }

                $pesanan->save();
                Log::info('Order status updated', ['order_id' => $request->order_id, 'status' => $pesanan->status]);

                return response()->json(['success' => true]);
            } else {
                Log::warning('Order not found', ['order_id' => $request->order_id]);
            }
        } else {
            Log::warning('Signature key does not match', ['provided' => $request->signature_key, 'expected' => $hashed]);
        }

        return response()->json(['success' => false]);
    }

    public function finish(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:pesanan,kode_pemesanan',
            ]);

            $pesanan = Pesanan::where('kode_pemesanan', $request->order_id)->first();

            if (!$pesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ]);
            }

            $midtransStatus = \Midtrans\Transaction::status($request->order_id);

            if ($midtransStatus->transaction_status == 'settlement' || $midtransStatus->transaction_status == 'capture') {
                $pesanan->status = 'dibayar';
                $pesanan->waktu_dibayar = now();
                $pesanan->metode_pembayaran = $this->getPaymentMethod($midtransStatus->payment_type);
                $pesanan->id_transaksi_midtrans = $midtransStatus->transaction_id;
                $pesanan->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status pesanan diperbarui menjadi "dibayar"',
                    'redirect_url' => route('riwayat')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran belum selesai',
                    'status' => $midtransStatus->transaction_status
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error updating order status', [
                'error' => $e->getMessage(),
                'order_id' => $request->order_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status pesanan'
            ]);
        }
    }

    public function getStatus($orderId)
    {
        try {
            $pesanan = Pesanan::where('kode_pemesanan', $orderId)->firstOrFail();
            $midtransStatus = \Midtrans\Transaction::status($orderId);

            return response()->json([
                'order_status' => $pesanan->status,
                'payment_method' => $pesanan->metode_pembayaran,
                'midtrans_status' => $midtransStatus->transaction_status
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting order status: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
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
}