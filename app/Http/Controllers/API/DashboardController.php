<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardResource;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\Operator;
use App\Models\StatusActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
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
        // $operator = Operator::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $operator = Operator::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('id','DESC')->get();
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
        $operator = Operator::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('id','DESC')
        ->paginate(10);

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
    public function current_call_session_detail_information($start_date,$end_date,Request $request)
    {
        // $operator = Operator::whereBetween('created_at',[date($start_date), date($end_date)])
        $operator = Operator::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('date_to_call','DESC');

        $operator = $request->get('page') == 'all' ? $operator->get() : $operator->paginate(10);
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
    *       path="/api/dashboard/operator/total_agent/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="total_agent",
    *       summary="total_agent",
    *       description="total_agent",
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
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *       ),
    *  )
    */
    public function total_agent($start_date,$end_date)
    {
        $dataUser = [];

        $users = User::with(['Role','Status'])->orderBy('id', 'DESC')->whereHas('Role', function($userRole){
            $userRole->where('name', 'operator');
        })->get();

        foreach ($users as $key => $user) {
            $login = StatusActivityLog::where([
                ['status','=', 'online'],
                ['user_id','=', $user->id]
                ])->whereDate('created_at', '>=', date($start_date))->whereDate('created_at', '<=', date($end_date))->orderBy('id', 'DESC')->get();
            $logout = StatusActivityLog::where([
                ['status','=', 'offline'],
                ['user_id','=', $user->id]
                ])->whereDate('created_at', '>=', date($start_date))->whereDate('created_at', '<=', date($end_date))->orderBy('id', 'DESC')->get();
            $break = StatusActivityLog::where([
                ['status','=', 'break'],
                ['user_id','=', $user->id]
                ])->whereDate('created_at', '>=', date($start_date))->whereDate('created_at', '<=', date($end_date))->orderBy('id', 'DESC')->get();
            $dataUser[] = $user;

            $dataUser[$key]['duration_login'] = $this->countDurationStatus($login);
            $dataUser[$key]['duration_logout'] = $this->countDurationStatus($logout);
            $dataUser[$key]['duration_break'] = $this->countDurationStatus($break);
        }
        return response()->json(['data'=>$dataUser]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/count_category/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard count_category",
    *       summary="Dashboard count_category",
    *       description="Dashboard count_category",
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
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *       ),
    *  )
    */
    public function count_category($start_date,$end_date): JsonResponse
    {
        $category = Operator::whereDate('date_to_call', '>=', date($start_date))->whereDate('date_to_call', '<=', date($end_date))->groupBy('category')->selectRaw("category,
        count(*) as count_category,
        round((Count(category)* 100.0 / (
        select
            Count(*)
        from
            operators where date(date_to_call) >='".date($start_date)."' and date(date_to_call) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$category]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/operator/count_tag/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard count_tag",
    *       summary="Dashboard count_tag",
    *       description="Dashboard count_tag",
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
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *       ),
    *  )
    */
    public function count_tag($start_date,$end_date): JsonResponse
    {
        $tags = Operator::whereDate('date_to_call', '>=', date($start_date))->whereDate('date_to_call', '<=', date($end_date))->groupBy('tag')->selectRaw("tag,
        count(*) as count_tag,
        round((Count(tag)* 100.0 / (
        select
            Count(*)
        from
            operators where date(date_to_call) >='".date($start_date)."' and date(date_to_call) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$tags]);
    }
}
