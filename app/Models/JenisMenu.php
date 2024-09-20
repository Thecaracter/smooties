<?php

namespace App\Models;

use App\Events\JenisMenuUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    protected $dispatchesEvents = [
        'updated' => JenisMenuUpdated::class,
        // 'created' => JenisMenuUpdated::class,
        // 'deleted' => JenisMenuUpdated::class,
    ];
}
