<?php

use App\Http\Controllers\Api\ApiTestController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('tasks')->group(function () {
    Route::post('/', [TaskController::class, 'store']);
    Route::put('/task/status', [TaskController::class, 'updateStatus']);
    Route::get('/', [TaskController::class, 'index']);
});

Route::prefix('api-test')->group(function () {
    Route::get('/success', [ApiTestController::class, 'success']);
    Route::get('/error', [ApiTestController::class, 'error']);
    Route::get('/exception', [ApiTestController::class, 'exception']);
});
