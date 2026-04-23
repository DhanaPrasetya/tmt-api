<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::post('/shorten', [UrlController::class, 'shorten']);

Route::get('/', [UrlController::class, 'get_original_url']);
