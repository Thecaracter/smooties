<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone_number' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone_number' => '089876543210',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
