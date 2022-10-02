<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\TrustConnectionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PathController;

Route::post('/people', [PeopleController::class, 'store']);
Route::post('/people/{person_id}/trust_connections', [TrustConnectionController::class, 'store']);
Route::post('/messages', [MessageController::class, 'store']);
Route::post('/path', [PathController::class, 'store']);
