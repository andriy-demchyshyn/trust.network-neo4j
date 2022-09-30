<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\MessageController;

Route::apiResources([
    'people' => PeopleController::class,
    'messages' => MessageController::class,
]);