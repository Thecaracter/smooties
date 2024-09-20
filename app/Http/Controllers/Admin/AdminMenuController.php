<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Events\MenuUpdated;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('jenisMenu')->get();
        $categories = Kategori::all();
        return view('admin.menu', compact('menus', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'deskripsi' => 'required|string',
            'nama' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aktif' => 'required|boolean',
        ]);

        try {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('fotoMenu');

            $file->move($filePath, $fileName);

            Menu::create([
                'kategori_id' => $request->kategori_id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'foto' => $fileName,
                'aktif' => $request->aktif,
            ]);


            return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu.');
        }
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'deskripsi' => 'required|string',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aktif' => 'required|boolean',
        ]);

        $menu = Menu::findOrFail($id);

        try {
            // Check if a new photo is uploaded
            if ($request->hasFile('foto')) {
                // Delete the existing photo if it exists
                if ($menu->foto && File::exists(public_path('fotoMenu/' . $menu->foto))) {
                    File::delete(public_path('fotoMenu/' . $menu->foto));
                }

                // Handle the new photo upload
                $file = $request->file('foto');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = public_path('fotoMenu');
                $file->move($filePath, $fileName);
                $menu->foto = $fileName; // Update the menu's photo field
            }

            // Update other menu fields
            $menu->kategori_id = $request->kategori_id;
            $menu->nama = $request->nama;
            $menu->deskripsi = $request->deskripsi;
            $menu->aktif = $request->aktif;
            $menu->save(); // Save the updated menu

            event(new MenuUpdated($menu));

            return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui menu.');
        }
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        try {
            // Check if the photo file exists and delete it
            if ($menu->foto && File::exists(public_path('fotoMenu/' . $menu->foto))) {
                File::delete(public_path('fotoMenu/' . $menu->foto));
            }

            // Delete the menu record
            $menu->delete();

            return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus menu.');
        }
    }
}
