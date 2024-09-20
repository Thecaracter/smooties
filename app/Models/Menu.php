<?php

namespace App\Models;

use App\Events\MenuUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';

    protected $fillable = ['kategori_id', 'deskripsi', 'nama', 'foto', 'aktif'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }
    public function jenisMenu()
    {
        return $this->hasMany(JenisMenu::class);
    }
    protected $dispatchesEvents = [
        'updated' => MenuUpdated::class,
    ];
}
