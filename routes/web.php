<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/create', [HomeController::class, 'create'])->name('createRoute');
Route::post('/store', [HomeController::class, 'store'])->name('storeRoute');
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('editRoute');
Route::post('/update', [HomeController::class, 'update'])->name('updateRoute');
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroyRoute');
Route::get('/reply/{id}', [HomeController::class, 'reply'])->name('replyRoute');
Route::post('/comment', [HomeController::class, 'comment'])->name('commentRoute');
Route::get('/post/{id}', [HomeController::class, 'post'])->name('postRoute');
Route::get('/account/{id}', [HomeController::class, 'account'])->name('account');

