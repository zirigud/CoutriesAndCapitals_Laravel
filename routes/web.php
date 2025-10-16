<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

//start game

Route::get('/', [MainController::class, 'startGame'])->name('startGame');
Route::post('/', [MainController::class, 'prepareGame'])->name('prepareGame');


//game

Route::get('/game', [MainController::class, 'game'])->name('game');
Route::get('/answer/{answer}', [MainController::class, 'answer'])->name('answer');
Route::get('/next', [MainController::class, 'nextQuestion'])->name('next_question');
Route::get('/show_results', [MainController::class, 'showResults'])->name('show_results');
