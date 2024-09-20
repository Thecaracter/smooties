<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminRiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Pesanan::where('status', 'selesai')->get();
        return view('admin.riwayat', compact('riwayats'));
    }
}
