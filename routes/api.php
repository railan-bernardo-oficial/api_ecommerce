<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::middleware(['api'])->prefix('painel')->group(function () {

    Route::post('/account/create', [UserController::class, 'store']);
});

Route::middleware(['auth'])->prefix('painel')->group(function () {

    // usuários
    Route::get('/users', [UserController::class, 'listUsers']);
    Route::put('/account/update/{id}', [UserController::class, 'update']);
    Route::delete('/account/delete/{id}', [UserController::class, 'delete']);

    // endereços
    Route::get('/address/{id}', [AddressController::class, 'findAddress']);
    Route::post('/address/create', [AddressController::class, 'store']);
    Route::put('/address/update/{id}', [AddressController::class, 'update']);
    Route::delete('/address/delete/{id}', [AddressController::class, 'delete']);
});
