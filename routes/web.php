<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\UserMenuController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPesananController;
use App\Http\Controllers\Admin\AdminRiwayatController;
use App\Http\Controllers\Admin\AdminDashboarController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminJenisMenuController;

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

Route::get('/', [UserHomeController::class, 'index']);

Route::get('/menu', [UserMenuController::class, 'index'])->name('menu');
Route::get('/keranjang', function () {
    return view('landing.keranjang');
})->name('keranjang');
Route::get('/riwayat', function () {
    return view('landing.riwayat');
})->name('riwayat');

//Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth-login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth-register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    //Dashboard Routes
    Route::get('/dashboard', [AdminDashboarController::class, 'index'])->name('dashboard');

    //User Routes
    Route::get('/user', [AdminUserController::class, 'index'])->name('user.index');
    Route::delete('/user/{id}', [AdminUserController::class, 'destroy'])->name('user.destroy');

    //Kategori Routes
    Route::get('/kategori', [AdminKategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [AdminKategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [AdminKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [AdminKategoriController::class, 'destroy'])->name('kategori.destroy');

    //Menu Routes
    Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [AdminMenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');

    //Jenis Menu Routes
    Route::get('jenis-menu/{menu_id}', [AdminJenisMenuController::class, 'index'])->name('admin.jenis-menu.index');
    Route::post('jenis-menu/{menu_id}', [AdminJenisMenuController::class, 'store'])->name('admin.jenis-menu.store');
    Route::put('jenis-menu/{id}', [AdminJenisMenuController::class, 'update'])->name('admin.jenis-menu.update');
    Route::delete('jenis-menu/{id}', [AdminJenisMenuController::class, 'destroy'])->name('admin.jenis-menu.destroy');

    //Pesanan Routes
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('admin.pesanan.index');
    Route::put('/pesanan/{id}/update-status', [AdminPesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');

    //Riwayat Routes
    Route::get('/riwayat', [AdminRiwayatController::class, 'index'])->name('admin.riwayat.index');
});