<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route group auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::post('/create-order', function(){
    return 'create order';
})->middleware(['auth:sanctum', 'ableCreateOrder']);

Route::post('/finish-order', function(){
    return 'finish order';
})->middleware(['auth:sanctum', 'ableFinishOrder']);