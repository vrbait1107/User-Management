<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authentication;

Route::middleware([Authentication::class])->group(function () {
    Route::apiResource('users', UserController::class);
});