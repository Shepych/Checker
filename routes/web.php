<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
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

### Главная страница чеккера
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/panel', [AdminController::class, 'panel'])->name('panel');
Route::post('/check', [MainController::class, 'check'])->name('check');
Route::post('/section/{id}', [MainController::class, 'section'])->name('section');
