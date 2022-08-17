<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

### Главная страница чеккера
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/panel', [AdminController::class, 'panel'])->name('panel');
Route::post('/check', [MainController::class, 'check'])->name('check');
Route::post('/subsection/{id}', [MainController::class, 'subsection'])->name('subsection');
Route::post('/section/{id}', [MainController::class, 'section'])->name('section');
