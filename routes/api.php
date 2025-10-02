<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route group auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/user', [UserController::class, 'store'])->middleware('ableCreateUser');

    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store'])->middleware('ableCreateUpdateItem');
    Route::patch('/item/{id}', [ItemController::class, 'update'])->middleware('ableCreateUpdateItem');


    Route::get('/order/{id}/set-as-done', [OrderController::class, 'setAsDone'])->middleware(['ableFinishOrder']);
    Route::get('/order/{id}/payment', [OrderController::class, 'payment'])->middleware(['ablePayOrder']);
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::post('/order', [OrderController::class, 'store'])->middleware(['ableCreateOrder']);
});
