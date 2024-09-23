<?php

namespace App\Http\Controllers\User;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Komentar;
use App\Models\JenisMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserHomeController extends Controller
{
    public function index()
    {
        // Ambil 3 menu terlaris berdasarkan jumlah pesanan
        $topMenus = Menu::with(['kategori', 'jenisMenu', 'komentar.user'])
            ->select('menu.*', DB::raw('COUNT(detail_pesanan.id) as order_count'))
            ->leftJoin('jenis_menu', 'menu.id', '=', 'jenis_menu.menu_id')
            ->leftJoin('detail_pesanan', 'jenis_menu.id', '=', 'detail_pesanan.jenis_menu_id')
            ->groupBy('menu.id', 'menu.nama', 'menu.deskripsi', 'menu.foto', 'menu.kategori_id', 'menu.created_at', 'menu.updated_at', 'menu.aktif')
            ->orderBy('order_count', 'desc')
            ->take(3)
            ->get();

        // Jika tidak ada menu terlaris, ambil 3 menu teratas
        if ($topMenus->isEmpty()) {
            $topMenus = Menu::with(['kategori', 'jenisMenu', 'komentar.user'])
                ->take(3)
                ->get();
        }

        foreach ($topMenus as $menu) {
            // Ambil 2 komentar terbaru untuk setiap menu
            $menu->recent_comments = $menu->komentar()
                ->with('user:id,username')
                ->latest()
                ->take(2)
                ->get();
        }

        return view('landing.home', compact('topMenus'));
    }
}
