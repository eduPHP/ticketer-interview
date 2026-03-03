<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post(
    '/events/{event}/reservations',
    \App\Http\Controllers\CreateReservationController::class
)->name('reservations.create');
