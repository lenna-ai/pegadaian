<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardHelpdeskResource;
use App\Http\Resources\Helpdesk\HelpDeskResource;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\HelpDesk;
use App\Models\Operator;
use App\Models\StatusActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardHelpdeskController extends Controller
{
    public function index(): string
    {
        $this->authorize('read', User::class);
        return 'ok';
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/total_call/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="total_call_Helpdesk",
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
    *                   "count_helpdesk": "integer",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function total_call($start_date,$end_date)
    {
        // $helpdesk = HelpDesk::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $helpdesk = HelpDesk::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('id','DESC')->get();
        $result_helpdesk['count_helpdesk'] = count($helpdesk);
        return new DashboardHelpdeskResource((object)$result_helpdesk);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/average_call_time/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="average_call_time_Helpdesk",
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
        // $helpdesk = HelpDesk::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $helpdesk = HelpDesk::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('id','DESC')
        ->get();

        if (!count($helpdesk)) {
            $result = 0;
        } else {
            $count_helpdesk = count($helpdesk);
            $sumHelpdesk = $helpdesk->sum('call_duration');

            $result = $sumHelpdesk / $count_helpdesk;
        }
        $result_helpdesk = [
            'average_call_time' => $result
        ];
        return new DashboardHelpdeskResource((object)$result_helpdesk);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/current_call_session_detail_information/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="current_call_session_detail_information_Helpdesk",
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
        // $helpdesk = HelpDesk::whereDate('date_to_call',[date($start_date), date($end_date)])->where(['name_agent'=>auth()->user()->name])->get();
        $helpdesk = HelpDesk::whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('date_to_call','DESC');

        $helpdesk = $request->get('page') == 'all' ? $helpdesk->get() : $helpdesk->paginate(10);
        return HelpDeskResource::collection($helpdesk);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/performance_hourly_today/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="performance_hourly_today_Helpdesk",
    *       summary="performance_hourly_today",
    *       description="performance_hourly_today",
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *      ),
    *  )
    */
    public function performance_hourly_today($start_date,$end_date)
    {

        // $data = HelpDesk::whereDate('created_at', Carbon::today()->toDateString())->get()->groupBy(function($date) {
        $data = HelpDesk::whereDate('date_to_call','>=', date($start_date))->whereDate('date_to_call', '<=', date($end_date))->get()->groupBy(function($date) {
            return Carbon::parse($date->date_to_call)->format('H');
        });
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/total_agent/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="total_agent_Helpdesk",
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
        // $users = User::orderBy('id', 'DESC')->get();
        $users = User::with(['Role','Status'])->orderBy('id', 'DESC')->whereHas('Role', function($userRole){
            $userRole->where('name', 'help_desk');
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
        return $dataUser;
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/list_helpdesk/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="list_helpdesk_Helpdesk",
    *       summary="list_helpdesk",
    *       description="list_helpdesk",
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
    public function list_helpdesk($start_date,$end_date,Request $request)
    {
        $data = HelpDesk::whereDate('date_to_call', '>=', $start_date)
        ->whereDate('date_to_call', '<=', $end_date);

        $data = $request->get('page') == 'all' ? $data->get() : $data->paginate(10);

        return HelpDeskResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/count_category/{start_date}/{end_date}",
    *       tags={"Dashboard Helpdesk"},
    *       operationId="Helpdesk count_category",
    *       summary="Helpdesk count_category",
    *       description="Helpdesk count_category",
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
        $category = HelpDesk::whereDate('date_to_call', '>=', date($start_date))->whereDate('date_to_call', '<=', date($end_date))->groupBy('category')->selectRaw("category,
        count(*) as count_category,
        round((Count(tag)* 100.0 / (
        select
            Count(*)
        from
            help_desks where date(date_to_call) >='".date($start_date)."' and date(date_to_call) <='".date($end_date)."')),2) as percentage")->get();

        // $distinctOperator = HelpDesk::distinct()->get(['category']);
        // $data = [];

        // foreach ($distinctOperator as $key => $valueDistinctOperator) {
        //     $distinctOperator[$valueDistinctOperator->category] = true;
        //     unset($distinctOperator[$key]);
        //     foreach ($category as $keycategory => $valuecategory) {
        //         if ($valueDistinctOperator->category == $valuecategory->category) {
        //             $distinctOperator[$valueDistinctOperator->category] = false;
        //             unset($distinctOperator[$keycategory]);
        //         }
        //     }
        //     if ($distinctOperator[$valueDistinctOperator->category]) {
        //         $data[] = $valueDistinctOperator;
        //     }
        // }

        // foreach ($data as $key => $value) {
        //     $category[] = [
        //         'category' => $value->category,
        //         'count_category' => 0,
        //         'percentage' => 0,
        //     ];
        // }
        return response()->json(['data'=>$category]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/helpdesk/count_status/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard helpdesk count_status",
    *       summary="Dashboard helpdesk count_status",
    *       description="Dashboard helpdesk count_status",
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
    public function count_status($start_date,$end_date): JsonResponse
    {
        $status = HelpDesk::whereDate('date_to_call', '>=', date($start_date))->whereDate('date_to_call', '<=', date($end_date))->groupBy('status')->selectRaw("status,
        count(*) as count_status,
        round((Count(status)* 100.0 / (
        select
            Count(*)
        from
            help_desks where date(date_to_call) >='".date($start_date)."' and date(date_to_call) <='".date($end_date)."')),2) as percentage")->get();

        // $distinctHelpDesk = HelpDesk::distinct()->get(['tag']);
        // $data = [];

        // foreach ($distinctHelpDesk as $key => $valueDistinctHelpDesk) {
        //     $distinctHelpDesk[$valueDistinctHelpDesk->tag] = true;
        //     unset($distinctHelpDesk[$key]);
        //     foreach ($tags as $keyTag => $valueTag) {
        //         if ($valueDistinctHelpDesk->tag == $valueTag->tag) {
        //             $distinctHelpDesk[$valueDistinctHelpDesk->tag] = false;
        //             unset($distinctHelpDesk[$keyTag]);
        //         }
        //     }
        //     if ($distinctHelpDesk[$valueDistinctHelpDesk->tag]) {
        //         $data[] = $valueDistinctHelpDesk;
        //     }
        // }

        // foreach ($data as $key => $value) {
        //     $tags[] = [
        //         'tag' => $value->tag,
        //         'count_tag' => 0,
        //         'percentage' => 0,
        //     ];
        // }
        return response()->json(['data'=>$status]);
    }
}
