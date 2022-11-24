<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\CancelController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// car router
Route::get('/car/{id}', [CarController::class, 'show']);
Route::get('/car', [CarController::class, 'index']);

// must login
Route::middleware('auth:sanctum')->group(function(){
    // == only admin ==
    // car
    Route::post('/car/{id}', [CarController::class, 'update'])->middleware('admin');
    Route::post('/car', [CarController::class, 'store'])->middleware('admin');
    Route::delete('/car/{id}', [CarController::class, 'destroy'])->middleware('admin');
    // costumer
    Route::get('/costumer/detail', [CostumerController::class, 'all_detail_user'])->middleware('admin');
    Route::get('/costumer/detail/{id}', [CostumerController::class, 'detail_user'])->middleware('admin');
    // order
    Route::put('/order/start_rental/{id}', [OrderController::class, 'start_rental'])->middleware('admin');
    Route::get('/order', [OrderController::class, 'index'])->middleware('admin');
    // == all users ==
    // order
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::post('/order/make_order', [OrderController::class, 'make_order']);
    Route::put('/order/end_rental/{id}', [OrderController::class, 'end_rental']); 
    // cancel
    Route::post('/cancel', [CancelController::class, 'cancel']);
    Route::get('/cancel/{id}', [CancelController::class, 'reason']);
    // costumer
    Route::get('/costumer/myprofile', [CostumerController::class, 'my_profile']);
    Route::post('/costumer', [CostumerController::class, 'add_costumer']);
    Route::put('/costumer/update', [CostumerController::class, 'update']);
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/costumer', [CostumerController::class, 'add_costumer']);
});
