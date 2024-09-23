<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemesanan')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_harga');
            $table->string('metode_pembayaran')->nullable();
            $table->string('id_transaksi_midtrans')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('status', ['diproses', 'dibayar', 'diantar', 'selesai', 'dibatalkan'])->default('diproses');
            $table->timestamp('waktu_diproses')->nullable();
            $table->timestamp('waktu_dibayar')->nullable();
            $table->timestamp('waktu_diantar')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
