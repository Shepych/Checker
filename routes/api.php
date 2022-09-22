<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/index', [MainController::class, 'apiIndex']);
Route::get('/section/{id}', [MainController::class, 'apiSection']);
Route::get('/subsection/{id}', [MainController::class, 'apiSubsection']);
Route::get('/answer', [MainController::class, 'apiAnswer']);
