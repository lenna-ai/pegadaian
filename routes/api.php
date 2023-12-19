<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\DashboardHelpdeskController;
use App\Http\Controllers\API\HelpDeskController;
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
    Route::post('change-status', [AuthController::class, 'change_status']);
});

Route::group(['middleware' => 'auth:api'],function (): void {
    Route::group(['prefix'=>'dashboard'],function () {

        Route::group(['prefix'=>'operator'],function () {
            Route::get('total_call/{start_date}/{end_date}', [DashboardController::class, 'total_call'])->middleware(['can:admin']);
            Route::get('average_call_time/{start_date}/{end_date}', [DashboardController::class, 'average_call_time'])->middleware(['can:admin']);
            Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardController::class, 'current_call_session_detail_information'])->middleware(['can:admin']);
            Route::get('performance_hourly_today', [DashboardController::class, 'performance_hourly_today'])->middleware(['can:admin']);
            Route::get('total_agent', [DashboardController::class, 'total_agent'])->middleware(['can:admin']);
        });

        Route::group(['prefix'=>'helpdesk'],function () {
            Route::get('total_call/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'total_call'])->middleware(['can:admin']);
            Route::get('average_call_time/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'average_call_time'])->middleware(['can:admin']);
            Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'current_call_session_detail_information'])->middleware(['can:admin']);
            Route::get('performance_hourly_today', [DashboardHelpdeskController::class, 'performance_hourly_today'])->middleware(['can:admin']);
            Route::get('total_agent', [DashboardHelpdeskController::class, 'total_agent'])->middleware(['can:admin']);
            Route::get('list_helpdesk/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'list_helpdesk'])->middleware(['can:admin']);
        });
    });

    Route::group(['prefix' => 'user'],function () {
        Route::post('/', [UserController::class, 'create'])->middleware(['can:admin']);
        Route::get('/', [UserController::class, 'index'])->middleware(['can:admin']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete'])->middleware(['can:admin']);
    });

    Route::group(['prefix' => 'operator'],function () {
        Route::post('/', [OperatorController::class, 'create'])->middleware(['can:operator']);
        Route::get('/', [OperatorController::class, 'index'])->middleware(['can:operator']);
    });

    Route::group(['prefix' => 'helpdesk'],function () {
        Route::post('/', [HelpDeskController::class, 'create'])->middleware(['can:help_desk']);
        Route::get('/', [HelpDeskController::class, 'index'])->middleware(['can:help_desk']);

        Route::group(['prefix' => 'outlet'],function () {
            Route::get('/status', [HelpDeskController::class, 'status'])->middleware(['can:help_desk']);
            Route::get('/parent_branch', [HelpDeskController::class, 'parent_branch'])->middleware(['can:help_desk']);
            Route::get('/outlet_name', [HelpDeskController::class, 'outlet_name'])->middleware(['can:help_desk']);
            Route::get('/branch_code', [HelpDeskController::class, 'branch_code'])->middleware(['can:help_desk']);

        });
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['can:admin','throttle:login']);
    Route::get('outlet/category', [HelpDeskController::class, 'category'])->middleware(['can:operator,help_desk']);
    Route::get('outlet/tag', [HelpDeskController::class, 'tag'])->middleware(['can:operator,help_desk']);
});
