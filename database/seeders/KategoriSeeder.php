<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama' => 'Fruit Smoothies', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Green Smoothies', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Protein Smoothies', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
