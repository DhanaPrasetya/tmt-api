<?php

use App\Http\Controllers\LinksController;
use Illuminate\Support\Facades\Route;

Route::post('/shorten', [LinksController::class, 'shorten']);

Route::get('/s/{id}', [LinksController::class, 'short_link']);
