<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            [
                'kategori_id' => 1,
                'nama' => 'Berry Blast',
                'deskripsi' => 'Campuran stroberi, raspberry, dan blueberry.',
                'foto' => '1.jpeg',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'nama' => 'Green Machine',
                'deskripsi' => 'Campuran bayam, apel, dan jeruk.',
                'foto' => '2.jpeg',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'nama' => 'Protein Power',
                'deskripsi' => 'Campuran whey protein, pisang, dan susu.',
                'foto' => '3.jpeg',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
