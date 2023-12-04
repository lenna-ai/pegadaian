<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardResource;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\Operator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): string
    {
        $this->authorize('read', User::class);
        return 'ok';
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/total_call/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="total_call",
    *       summary="total_call",
    *       description="total_call",
    *     @OA\Parameter(
    *         description="Parameter start_date examples",
    *         in="path",
    *         name="start_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-22", summary="An string date value."),
    *     ),
    *     @OA\Parameter(
    *         description="Parameter end_date examples",
    *         in="path",
    *         name="end_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-30", summary="An string date value."),
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "count_operator": "integer",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function total_call($start_date,$end_date)
    {
        $operator = Operator::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $result_operator['count_operator'] = count($operator);
        return new DashboardResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/average_call_time/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="average_call_time",
    *       summary="average_call_time",
    *       description="average_call_time for minutes",
    *     @OA\Parameter(
    *         description="Parameter start_date examples",
    *         in="path",
    *         name="start_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-22", summary="An string date value."),
    *     ),
    *     @OA\Parameter(
    *         description="Parameter end_date examples",
    *         in="path",
    *         name="end_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-30", summary="An string date value."),
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "average_call_time": "integer",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function average_call_time($start_date,$end_date)
    {
        // $operator = Operator::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $operator = Operator::whereDate('created_at', '>=', date($start_date))
        ->whereDate('created_at', '<=', date($end_date))
        ->get();
        
        if (!count($operator)) {
            $result = 0;
        } else {
            $count_operator = count($operator);
            $sumOperator = $operator->sum('call_duration');
    
            $result = $sumOperator / $count_operator;
        }
        $result_operator = [
            'average_call_time' => $result
        ];
        return new DashboardResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/current_call_session_detail_information/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="current_call_session_detail_information",
    *       summary="current_call_session_detail_information",
    *       description="it is API for 2 Api Current Call Session & Dashboard Detail Information",
    *     @OA\Parameter(
    *         description="Parameter start_date examples",
    *         in="path",
    *         name="start_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-22", summary="An string date value."),
    *     ),
    *     @OA\Parameter(
    *         description="Parameter end_date examples",
    *         in="path",
    *         name="end_date",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="2023-11-30", summary="An string date value."),
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                  "name_agent": "admin",
    *                    "name_customer": "email@gmail.com",
    *                    "date_to_call": "22/10/2023",
    *                    "call_duration": 22,
    *                    "result_call": "sdddsdds"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function current_call_session_detail_information($start_date,$end_date)
    {
        $operator = Operator::whereBetween('created_at',[date($start_date), date($end_date)])
        ->where(['name_agent'=>auth()->user()->name])->get();
        return OperatorResource::collection($operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/performance_hourly_today/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="performance_hourly_today",
    *       summary="performance_hourly_today",
    *       description="performance_hourly_today",
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *      ),
    *  )
    */
    public function performance_hourly_today()
    {
        $data = Operator::where('date_to_call', '>=', Carbon::yesterday()->subDay())->get()->groupBy(function($date) {
            return Carbon::parse($date->date_to_call)->format('H');
        });
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/total_agent",
    *       tags={"Dashboard"},
    *       operationId="total_agent",
    *       summary="total_agent",
    *       description="total_agent",
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *       ),
    *  )
    */
    public function total_agent()
    {
        $dataUser = [];
        $users = User::all();
        foreach ($users as $key => $user) {
            $start = Carbon::parse($user->login_at);
            $end = Carbon::parse($user->logout_at);
            $durationLogin = $end->diffForHumans($start);
            $durationLogout = $start->diffForHumans($end);
            $dataUser[] = $user;
            $dataUser[$key]['duration_login'] = $durationLogin;
            $dataUser[$key]['duration_logout'] = $durationLogout;
        }
        return $dataUser;
    }
}
