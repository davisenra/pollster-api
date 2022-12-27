<?php

use App\Http\Controllers\API\v1\PollController;
use Illuminate\Support\Facades\Route;

Route::controller(PollController::class)->group(function () {
    Route::get('/polls', 'index');
    Route::get('/polls/{id}', 'show')->where('id', '[0-9]+');
    Route::get('/polls/{id}/results', 'results')->where('id', '[0-9]+');
    Route::post('/polls', 'store');
    Route::delete('/polls/{id}', 'destroy')->where('id', '[0-9]+');
});
