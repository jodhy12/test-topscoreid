<?php

use App\Http\Controllers\KaryawanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [KaryawanController::class, 'index'])->name('karyawan');

Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
Route::get('/kehadiran', [KaryawanController::class, 'kehadiran'])->name('kehadiran');
Route::get('/kehadiran/add', [KaryawanController::class, 'addKehadiran'])->name('addKehadiran');
Route::post('/kehadiran', [KaryawanController::class, 'store'])->name('store');
