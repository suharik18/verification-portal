<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\UserAuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/verify', [\App\Http\Controllers\VerifyController::class, 'verify']);
});
