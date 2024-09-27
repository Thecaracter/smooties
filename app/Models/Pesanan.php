<?php

namespace App\Models;

use App\Events\PesananUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'kode_pemesanan',
        'total_harga',
        'metode_pembayaran',
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
    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }
    protected $dispatchesEvents = [
        'updated' => PesananUpdated::class,
    ];

}
