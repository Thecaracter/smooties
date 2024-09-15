<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';

    protected $fillable = ['kategori_id', 'nama', 'foto', 'aktif'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
