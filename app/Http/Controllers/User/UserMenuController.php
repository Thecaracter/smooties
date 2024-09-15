<?php

namespace App\Http\Controllers\User;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserMenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu yang aktif
        $allMenus = Menu::with([
            'kategori',
            'jenisMenu' => function ($query) {
                $query->where('stok', '>', 0); // Filter hanya jenis menu dengan stok lebih dari 0
            },
            'komentar.user'
        ])
            ->where('aktif', true) // Filter hanya menu yang aktif
            ->get();

        foreach ($allMenus as $menu) {
            $menu->recent_comments = $menu->komentar()
                ->with('user:id,username')
                ->latest()
                ->get();
        }

        return view('landing.menu', compact('allMenus'));
    }

}
