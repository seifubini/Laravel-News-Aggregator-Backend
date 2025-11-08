<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticlesController;


Route::get('/articles', [ArticlesController::class, 'index']);
Route::get('/articles/{id?}', [ArticlesController::class, 'show']);
