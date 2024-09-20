<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\JenisMenu;

class JenisMenuUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jenisMenu;

    public function __construct(JenisMenu $jenisMenu)
    {
        $this->jenisMenu = $jenisMenu;
    }

    public function broadcastOn()
    {
        return new Channel('jenis-menu');
    }

    public function broadcastAs()
    {
        return 'jenis-menu-updated';
    }

    public function broadcastWith()
    {
        return [
            'jenisMenu' => $this->jenisMenu->load('menu'),
            'menu' => $this->jenisMenu->menu->load('kategori', 'jenisMenu', 'komentar.user'),
        ];
    }
}