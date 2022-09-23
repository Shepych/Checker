<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/index', [MainController::class, 'apiIndex']);
Route::get('/section', [MainController::class, 'apiSection']);
Route::get('/subsection', [MainController::class, 'apiSubsection']);
Route::get('/answer', [MainController::class, 'apiAnswer']);
