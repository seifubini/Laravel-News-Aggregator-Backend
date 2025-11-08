<?php

use App\Http\Controllers\Api\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/articles', [ArticlesController::class, 'index']);
