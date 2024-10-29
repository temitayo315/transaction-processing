<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('user-token', [UserController::class, 'generateToken']);
Route::post('user-registration', [UserController::class, 'registerUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/balance', [UserController::class, 'balance']);
});