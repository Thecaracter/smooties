<?php

namespace App\Http\Controllers\Admin;

use App\Models\JenisMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminJenisMenuController extends Controller
{
    public function index($menu_id)
    {
        try {
            $jenisMenu = JenisMenu::where('menu_id', $menu_id)->get();
            return view('admin.jenis-menu', compact('jenisMenu', 'menu_id'));
        } catch (\Exception $e) {
            Log::error('Gagal mengambil jenis menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to retrieve menu types: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $menu_id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        try {
            JenisMenu::create([
                'menu_id' => $menu_id,
                'jenis' => $request->jenis,
                'harga' => $request->harga,
                'stok' => $request->stok,
            ]);

            Log::info('Jenis menu berhasil ditambahkan.', [
                'menu_id' => $menu_id,
                'jenis' => $request->jenis,
            ]);

            return redirect()->route('admin.jenis-menu.index', $menu_id)->with('success', 'Jenis menu berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan jenis menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add menu type: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jenisMenu = JenisMenu::findOrFail($id);

            $request->validate([
                'jenis' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $jenisMenu->update([
                'jenis' => $request->jenis,
                'harga' => $request->harga,
                'stok' => $request->stok,
            ]);

            Log::info('Jenis menu berhasil diperbarui.', [
                'id' => $id,
                'jenis' => $request->jenis,
            ]);

            return redirect()->route('admin.jenis-menu.index', $jenisMenu->menu_id)->with('success', 'Jenis menu berhasil diperbarui.');
        } catch (ModelNotFoundException $e) {
            Log::error('Jenis menu tidak ditemukan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Menu type not found: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui jenis menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update menu type: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $jenisMenu = JenisMenu::findOrFail($id);
            $menu_id = $jenisMenu->menu_id;
            $jenisMenu->delete();

            Log::info('Jenis menu berhasil dihapus.', [
                'id' => $id,
            ]);

            return redirect()->route('admin.jenis-menu.index', $menu_id)->with('success', 'Jenis menu berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            Log::error('Jenis menu tidak ditemukan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Menu type not found: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jenis menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete menu type: ' . $e->getMessage());
        }
    }
}