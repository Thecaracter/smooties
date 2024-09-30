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

            $menu->recent_comments = $menu->komentar()
                ->with('user:id,username')
                ->latest()
                ->take(2)
                ->get();
        }

        return view('landing.menu', compact('allMenus'));
    }

}
