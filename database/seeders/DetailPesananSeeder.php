<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\JenisMenu;
use App\Models\DetailPesanan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pesanans = Pesanan::all();
        $jenisMenus = JenisMenu::all();

        foreach ($pesanans as $pesanan) {
            $jumlahItem = rand(1, 5); // Setiap pesanan memiliki 1-5 item

            for ($i = 0; $i < $jumlahItem; $i++) {
                $jenisMenu = $jenisMenus->random();
                $jumlah = rand(1, 3);
                $harga = $jenisMenu->harga; // Asumsikan ada kolom harga di tabel jenis_menu

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'jenis_menu_id' => $jenisMenu->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $jumlah * $harga,
                ]);
            }

            // Update total_harga di tabel pesanan
            $totalHarga = DetailPesanan::where('pesanan_id', $pesanan->id)->sum('subtotal');
            $pesanan->update(['total_harga' => $totalHarga]);
        }
    }
}
