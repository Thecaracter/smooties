<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AdminKategoriController extends Controller
{
    public function index()
    {
        $categories = Kategori::all();
        return view('admin.kategori', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            Kategori::create($request->all()); // Create a new category
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan kategori.');
        }
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $category = Kategori::findOrFail($id); // Find the category by ID

        try {
            $category->update($request->all()); // Update the category
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui kategori.');
        }
    }
    public function destroy(string $id)
    {
        $category = Kategori::findOrFail($id); // Find the category by ID

        try {
            $category->delete(); // Delete the category
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
