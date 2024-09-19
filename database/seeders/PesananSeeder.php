<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = ['cash', 'credit_card', 'bank_transfer'];

        // Fetch existing user IDs
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 30));
            $dibayarAt = $createdAt->copy()->addMinutes(rand(5, 60));

            $pesanan = [
                'kode_pemesanan' => 'ORDPESANAN-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $userIds[array_rand($userIds)], // Randomly select an existing user ID
                'total_harga' => rand(50000, 500000),
                'metode_pembayaran' => $paymentMethods[array_rand($paymentMethods)],
                'id_transaksi_midtrans' => 'MID' . str_pad($i, 13, '0', STR_PAD_LEFT),
                'latitude' => mt_rand(-90000000, 90000000) / 1000000,
                'longitude' => mt_rand(-180000000, 180000000) / 1000000,
                'status' => 'dibayar',
                'waktu_diproses' => $createdAt,
                'waktu_dibayar' => $dibayarAt,
                'waktu_diantar' => null,
                'waktu_selesai' => null,
                'created_at' => $createdAt,
                'updated_at' => $dibayarAt,
            ];

            DB::table('pesanan')->insert($pesanan);
        }
    }
}
