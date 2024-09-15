<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
        'id_transaksi_midtrans',
        'latitude',
        'longitude',
        'status',
        'waktu_diproses',
        'waktu_dibayar',
        'waktu_diantar',
        'waktu_selesai'
    ];

    protected $casts = [
        'waktu_diproses' => 'datetime',
        'waktu_dibayar' => 'datetime',
        'waktu_diantar' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
