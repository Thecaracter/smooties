<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_menu')->insert([
            [
                'menu_id' => 1,
                'jenis' => 'Small',
                'harga' => 15000,
                'stok' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 1,
                'jenis' => 'Medium',
                'harga' => 20000,
                'stok' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 1,
                'jenis' => 'Large',
                'harga' => 25000,
                'stok' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 2,
                'jenis' => 'Small',
                'harga' => 18000,
                'stok' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 2,
                'jenis' => 'Medium',
                'harga' => 23000,
                'stok' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 3,
                'jenis' => 'Small',
                'harga' => 20000,
                'stok' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 3,
                'jenis' => 'Large',
                'harga' => 30000,
                'stok' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
