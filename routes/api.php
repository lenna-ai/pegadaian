<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\OperatorController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
Route::group(['middleware' => 'api','prefix' => 'auth'],function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'auth:api'],function (): void {
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['can:admin','throttle:login']);

    Route::group(['prefix' => 'user'],function () {
        Route::post('/', [UserController::class, 'create'])->middleware(['can:admin']);
        Route::get('/', [UserController::class, 'index'])->middleware(['can:admin']);
        Route::put('/{id}', [UserController::class, 'update'])->middleware(['can:admin']);
        Route::delete('/{id}', [UserController::class, 'delete'])->middleware(['can:admin']);
    });

    Route::group(['prefix' => 'operator'],function () {
        Route::post('/', [OperatorController::class, 'create']);
        Route::get('/', [OperatorController::class, 'index']);
    });
});
