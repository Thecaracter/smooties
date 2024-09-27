<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;
    protected $table = 'komentar';

    protected $fillable = [
        'user_id',
        'menu_id',
        'pesanan_id',
        'isi_komentar',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
