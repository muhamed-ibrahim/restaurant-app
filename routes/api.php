<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\Api\auth\AuthController;
use App\Http\Controllers\API\ReservationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('item', [ItemController::class, 'listAll']);
    Route::get('availabe-tables', [ReservationController::class, 'AvailabeTables']);
    Route::post('reserve-table', [ReservationController::class, 'reserveTable']);
    Route::post('make-order', [OrderController::class, 'makeOrder']);
    Route::post('checkout', [OrderController::class, 'checkout']);
});
