<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
// Route::get('/home', [PagesController::class, 'index']);
Route::view('home', 'index');
Route::resource('/blog', PostController::class);
