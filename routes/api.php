<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\DashboardHelpdeskController;
use App\Http\Controllers\API\DashboardOutboundController;
use App\Http\Controllers\API\HelpDeskController;
use App\Http\Controllers\API\OperatorController;
use App\Http\Controllers\API\OutBoundController;
use App\Http\Controllers\API\UserController;
// use App\Http\Controllers\DashboardOutboundController;
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

Route::post('change-status', [AuthController::class, 'change_status']);
Route::group(['middleware' => 'api','prefix' => 'auth'],function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'auth:api'],function (): void {
    Route::group(['prefix'=>'dashboard'],function () {

        Route::group(['prefix'=>'operator'],function () {
            Route::get('total_call/{start_date}/{end_date}', [DashboardController::class, 'total_call'])->middleware(['can:admin']);
            Route::get('average_call_time/{start_date}/{end_date}', [DashboardController::class, 'average_call_time'])->middleware(['can:admin']);
            Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardController::class, 'current_call_session_detail_information'])->middleware(['can:admin']);
            Route::get('performance_hourly_today', [DashboardController::class, 'performance_hourly_today'])->middleware(['can:admin']);
            Route::get('total_agent/{start_date}/{end_date}', [DashboardController::class, 'total_agent'])->middleware(['can:admin']);
            Route::get('count_category/{start_date}/{end_date}', [DashboardController::class, 'count_category'])->middleware(['can:admin']);
        });

        Route::group(['prefix'=>'helpdesk'],function () {
            Route::get('total_call/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'total_call'])->middleware(['can:admin']);
            Route::get('average_call_time/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'average_call_time'])->middleware(['can:admin']);
            Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'current_call_session_detail_information'])->middleware(['can:admin']);
            Route::get('performance_hourly_today', [DashboardHelpdeskController::class, 'performance_hourly_today'])->middleware(['can:admin']);
            Route::get('total_agent/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'total_agent'])->middleware(['can:admin']);
            Route::get('list_helpdesk/{start_date}/{end_date}', [DashboardHelpdeskController::class, 'list_helpdesk'])->middleware(['can:admin']);
            Route::get('count_category/{start_date}/{end_date}', [DashboardController::class, 'count_category'])->middleware(['can:admin']);
        });

        Route::group(['prefix'=>'outbound'],function () {
            Route::get('total_agent/{start_date}/{end_date}', [DashboardOutboundController::class, 'total_agent'])->middleware(['can:admin']);
            Route::get('count_category/{start_date}/{end_date}', [DashboardController::class, 'count_category'])->middleware(['can:admin']);
            Route::group(['prefix' => 'confirmation-ticket'], function() {
                Route::get('total_call/{start_date}/{end_date}', [DashboardOutboundController::class, 'total_call_confirmation_ticket'])->middleware(['can:admin']);
                Route::get('average_call_time/{start_date}/{end_date}', [DashboardOutboundController::class, 'average_call_time_confirmation_ticket'])->middleware(['can:admin']);
                Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardOutboundController::class, 'current_call_session_detail_information_confirmation_ticket'])->middleware(['can:admin']);
                Route::get('performance_hourly_today', [DashboardOutboundController::class, 'performance_hourly_today_confirmation_ticket'])->middleware(['can:admin']);
            });

            Route::group(['prefix' => '{page}'], function() {
                Route::get('total_call/{start_date}/{end_date}', [DashboardOutboundController::class, 'total_call'])->middleware(['can:admin']);
                Route::get('average_call_time/{start_date}/{end_date}', [DashboardOutboundController::class, 'average_call_time'])->middleware(['can:admin']);
                Route::get('current_call_session_detail_information/{start_date}/{end_date}', [DashboardOutboundController::class, 'current_call_session_detail_information'])->middleware(['can:admin']);
                Route::get('performance_hourly_today', [DashboardOutboundController::class, 'performance_hourly_today'])->middleware(['can:admin']);
            });
        });
    });

    Route::group(['prefix' => 'user'],function () {
        Route::post('/', [UserController::class, 'create'])->middleware(['can:admin']);
        Route::get('/', [UserController::class, 'index'])->middleware(['can:admin']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete'])->middleware(['can:admin']);
    });

    Route::group(['prefix' => 'operator'],function () {
        Route::post('/', [OperatorController::class, 'create'])->middleware(['can:operator'])->name('create-operator');
        Route::get('/', [OperatorController::class, 'index'])->middleware(['can:operator']);

        Route::post('/update/{id}', [OperatorController::class, 'update'])->middleware(['can:operator'])->name('update-operator');
        Route::get('/detail/{id}', [OperatorController::class, 'detail'])->middleware(['can:operator']);
    });

    Route::group(['prefix' => 'helpdesk'],function () {
        Route::post('/', [HelpDeskController::class, 'create'])->middleware(['can:help_desk']);
        Route::get('/', [HelpDeskController::class, 'index'])->middleware(['can:help_desk']);
        Route::post('/update/{helpdesk}', [HelpDeskController::class, 'update'])->middleware(['can:help_desk']);
        Route::get('/detail/{helpdesk}', [HelpDeskController::class, 'detail'])->middleware(['can:help_desk']);

        Route::group(['prefix' => 'outlet'],function () {
            Route::get('/statusTrack', [HelpDeskController::class, 'statusTrack'])->middleware(['can:help_desk']);
            Route::get('/parent_branch', [HelpDeskController::class, 'parent_branch'])->middleware(['can:help_desk']);
            Route::get('/outlet_name', [HelpDeskController::class, 'outlet_name'])->middleware(['can:help_desk']);
            Route::get('/branch_code', [HelpDeskController::class, 'branch_code'])->middleware(['can:help_desk']);
        });
    });

    Route::group(['prefix' => 'outlet'],function () {
        Route::group(['prefix' => 'category'],function () {
            Route::get('/helpdesk', [CategoryController::class, 'helpdesk']);
            Route::get('/operator', [CategoryController::class, 'operator']);
            Route::get('/outbound', [CategoryController::class, 'outbound']);
            Route::post('/', [CategoryController::class, 'create']);
        });
        Route::group(['prefix' => 'tag'],function () {
            Route::get('/', [HelpDeskController::class, 'tag']);
        });
    });

    Route::group(['prefix' => 'outbound'], function() {
        Route::post('/confirmation-ticket', [OutBoundController::class, 'createConfirmationTicket'])->middleware(['can:outbound']);
        Route::get('/confirmation-ticket', [OutBoundController::class, 'confirmationTicket'])->middleware(['can:outbound']);

        Route::post('/confirmation-ticket/update/{outbound}', [OutBoundController::class, 'updateConfirmationTicket'])->middleware(['can:outbound']);
        Route::get('/confirmation-ticket/detail/{outbound}', [OutBoundController::class, 'detailConfirmationTicket'])->middleware(['can:outbound']);

        Route::group(['prefix' => '{page}'], function() {
            Route::get('/outlet/statusTrack', [OutBoundController::class, 'statusTrack']);
            Route::post('/', [OutBoundController::class, 'create'])->middleware(['can:outbound']);
            Route::get('/', [OutBoundController::class, 'index'])->middleware(['can:outbound']);

            Route::post('/update/{outbound}', [OutBoundController::class, 'update'])->middleware(['can:outbound']);
            Route::get('/detail/{outbound}', [OutBoundController::class, 'detail'])->middleware(['can:outbound']);
        });
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['can:admin','throttle:login']);
});
