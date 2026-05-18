<?php

use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/newsletter', [NewsletterController::class, 'subscribe']);
