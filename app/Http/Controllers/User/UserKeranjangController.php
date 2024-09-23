<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserKeranjangController extends Controller
{
    public function index()
    {
        return view('landing.keranjang');
    }
}
