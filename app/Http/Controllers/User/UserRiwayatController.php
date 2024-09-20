<?php

namespace App\Http\Controllers\User;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserRiwayatController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('landing.riwayat', ['authenticated' => false]);
        }

        $user = Auth::user();
        $pesanan = Pesanan::with(['detailPesanan.jenisMenu.menu'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('landing.riwayat', ['pesanan' => $pesanan, 'authenticated' => true]);
    }
}
