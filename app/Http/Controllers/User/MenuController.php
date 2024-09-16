<?php

namespace App\Http\Controllers\User;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu
        $allMenus = Menu::with(['kategori', 'jenisMenu', 'komentar.user'])->get();

        foreach ($allMenus as $menu) {
            // Ambil 2 komentar terbaru untuk setiap menu
            $menu->recent_comments = $menu->komentar()
                ->with('user:id,username')
                ->latest()
                ->take(2) // Ambil 2 komentar terbaru
                ->get();
        }

        return view('landing.menu', compact('allMenus'));
    }

}
