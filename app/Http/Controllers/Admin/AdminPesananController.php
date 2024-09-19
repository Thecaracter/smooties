<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\PesananUpdated;

class AdminPesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::whereIn('status', ['dibayar', 'diantar'])
            ->with(['detailPesanan.jenisMenu.menu'])
            ->get();
        return view('admin.pesanan', compact('pesanans'));
    }
    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $newStatus = $request->input('status');
        $currentStatus = $pesanan->status;

        if (
            ($currentStatus === 'dibayar' && $newStatus === 'diantar') ||
            ($currentStatus === 'diantar' && $newStatus === 'selesai')
        ) {

            $pesanan->status = $newStatus;

            if ($newStatus === 'diantar') {
                $pesanan->waktu_diantar = now();
            } elseif ($newStatus === 'selesai') {
                $pesanan->waktu_selesai = now();
            }

            $pesanan->save();

            // Trigger the event
            event(new PesananUpdated($pesanan));

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Perubahan status tidak valid.');
        }
    }
}
