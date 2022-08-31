<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

### Главная страница чеккера
Route::get('/', [MainController::class, 'index'])->name('index');
### Админ панель
Route::get('/auth', [AdminController::class, 'auth'])->name('auth');
Route::get('/panel', [AdminController::class, 'panel'])->name('panel');
Route::post('/subsection/{id}', [MainController::class, 'subsection'])->name('subsection');
Route::post('/section/{id}', [MainController::class, 'section'])->name('section');
