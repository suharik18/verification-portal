<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\UserAuthController::class,'login']);
