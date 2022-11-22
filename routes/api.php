<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// car routes
Route::post('/car/{id}', [CarController::class, 'update']);
Route::post('/car', [CarController::class, 'store']);
Route::get('/car/{id}', [CarController::class, 'show']);
Route::get('/car', [CarController::class, 'index']);
Route::delete('/car/{id}', [CarController::class, 'destroy']);

// order routes
Route::get('/order', [OrderController::class, 'index']);
Route::post('/order/make_order', [OrderController::class, 'make_order']);
Route::put('/order/start_rental/{id}', [OrderController::class, 'start_rental']);
Route::post('/order/end_rental/{id}', [OrderController::class, 'end_rental']);