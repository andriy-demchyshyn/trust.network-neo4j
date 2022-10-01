<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\TrustConnectionController;
use App\Http\Controllers\MessageController;

Route::apiResources([
    'people' => PeopleController::class,
    'people.trust_connections' => TrustConnectionController::class,
    'messages' => MessageController::class,
]);