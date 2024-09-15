<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMenu extends Model
{
    use HasFactory;
    protected $table = 'jenis_menu';

    protected $fillable = [
        'menu_id',
        'jenis',
        'harga',
        'stok'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
