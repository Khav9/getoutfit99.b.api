<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

Route::get('/v1/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    /*
        protect route
    */
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/me', [AuthController::class, 'me']);
    });
});