<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth-login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth-register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/menu', function () {
    return view('landing.menu');
})->name('menu');
Route::get('/keranjang', function () {
    return view('landing.keranjang');
})->name('keranjang');
Route::get('/riwayat', function () {
    return view('landing.riwayat');
})->name('riwayat');