<?php

namespace App\Http\Controllers\User;

use App\Models\Pesanan;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function comment(Request $request, $id)
    {
        Log::info('Comment function called', ['request' => $request->all(), 'id' => $id]);

        try {
            $pesanan = Pesanan::findOrFail($id);

            $validatedData = $request->validate([
                'menu_id' => 'required|exists:menu,id',
                'rating' => 'required|array',
                'rating.*' => 'required|integer|min:1|max:5',
                'isi_komentar' => 'required|string',
            ]);

            Log::info('Validation passed', ['validatedData' => $validatedData]);

            $menuId = $request->input('menu_id');
            $rating = $request->input("rating.$menuId");

            // Check if a comment already exists for this menu, user, and order
            $existingComment = Komentar::where('user_id', auth()->id())
                ->where('menu_id', $menuId)
                ->where('pesanan_id', $id)
                ->first();

            if ($existingComment) {
                return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk menu ini dalam pesanan ini.');
            }

            $comment = Komentar::create([
                'user_id' => auth()->id(),
                'menu_id' => $menuId,
                'pesanan_id' => $id,
                'rating' => $rating,
                'isi_komentar' => $request->input('isi_komentar'),
            ]);

            Log::info('Comment created', ['comment' => $comment]);

            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in comment function', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan komentar. Silakan coba lagi.');
        }
    }
}
