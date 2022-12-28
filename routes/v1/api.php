<?php

use App\Http\Controllers\API\v1\PollController;
use App\Http\Controllers\API\v1\VoteController;
use Illuminate\Support\Facades\Route;

Route::controller(PollController::class)->group(function () {
    Route::get('/polls', 'index');
    Route::get('/polls/{id}', 'show')->where('id', '[0-9]+');
    Route::post('/polls', 'store');
    Route::delete('/polls/{id}', 'destroy')->where('id', '[0-9]+');
});

Route::controller(VoteController::class)->group(function () {
    Route::post('/vote', 'store');
});
