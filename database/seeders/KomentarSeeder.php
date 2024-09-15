<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KomentarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ambil semua ID user dan menu yang ada
        $userIds = User::pluck('id')->toArray();
        $menuIds = Menu::pluck('id')->toArray();

        // Buat 50 komentar acak
        for ($i = 0; $i < 10; $i++) {
            DB::table('komentar')->insert([
                'user_id' => $faker->randomElement($userIds),
                'menu_id' => $faker->randomElement($menuIds),
                'isi_komentar' => $faker->paragraph(),
                'rating' => $faker->numberBetween(1, 5),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
