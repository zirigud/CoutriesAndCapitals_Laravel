<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', function () {
    return view('home');
});

Route::get('/show-data', [MainController::class, 'showData']);
