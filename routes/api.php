<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;

Route::get('/user', [UserController::class, 'getUserData'])->middleware(['auth:api', CheckToken::using('user')]);
