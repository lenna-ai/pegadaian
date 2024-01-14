<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardOutboundResource;
use App\Http\Resources\Outbound\OutboundConfirmationTicketResource;
use App\Http\Resources\Outbound\OutboundResource;
use App\Models\OutBound;
use App\Models\OutBoundConfirmationTicket;
use App\Models\StatusActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardOutboundController extends Controller
{
    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/{page}/total_call/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="total_call_outbound",
    *       summary="total call outbound by page",
    *       description="total call outbound by page",
    *     @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *     ),
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
    *                   "count_outbound": "integer",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function total_call(string $page, $start_date,$end_date)
    {
        $outbound = OutBound::where('owned', 'outbound_' . $page)->whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('id','DESC')->get();
        $result_operator['count_outbound'] = count($outbound);
        return new DashboardOutboundResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/{page}/average_call_time/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="average_ca_outboundll_time",
    *       summary="average call time outbound by page for minutes",
    *       description="average call time outbound by page for minutes",
    *     @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *     ),
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
    public function average_call_time(string $page, $start_date,$end_date)
    {
        // $outbound = Operator::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $outbound = OutBound::where('owned', 'outbound_' . $page)->whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('id','DESC')
        ->get();

        if (!count($outbound)) {
            $result = 0;
        } else {
            $count_outbound = count($outbound);
            $sumOperator = $outbound->sum('call_duration');

            $result = $sumOperator / $count_outbound;
        }
        $result_operator = [
            'average_call_time' => $result
        ];
        return new DashboardOutboundResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/{page}/current_call_session_detail_information/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="current_ca_outboundll_session_detail_information",
    *       summary="current_call_session_detail_information",
    *       description="it is API for 2 Api Current Call Session & Dashboard Detail Information",
    *     @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *     ),
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
    *                    "call_time": "22/10/2023",
    *                    "call_duration": 22,
    *                    "result_call": "sdddsdds"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function current_call_session_detail_information(string $page, $start_date,$end_date,Request $request)
    {
        $outbound = OutBound::where('owned', 'outbound_' . $page)->whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('call_time','DESC');

        $outbound = $request->get('page') == 'all' ? $outbound->get() : $outbound->paginate(10);
        return OutboundResource::collection($outbound);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/{page}/performance_hourly_today/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="performanc_outbounde_hourly_today",
    *       summary="performance hourly today outbound by page",
    *       description="performance hourly today outbound by page",
    *     @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *     ),
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *      ),
    *  )
    */
    public function performance_hourly_today(string $page, $start_date,$end_date)
    {
            $data = OutBound::where('owned', 'outbound_' . $page)->whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->get()->groupBy(function($date) {
            return Carbon::parse($date->call_time)->format('H');
        });
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/total_agent/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="total_agen_outboundt",
    *       summary="total agent",
    *       description="total agent outbound",
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
            $userRole->where('name', 'outbound');
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
    *       path="/api/dashboard/outbound/confirmation-ticket/total_call/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="total_call_outbound_confirmation_ticket",
    *       summary="total call outbound confirmation ticket",
    *       description="total call outbound confirmation ticket",
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
    *                   "count_outbound": "integer",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function total_call_confirmation_ticket($start_date,$end_date)
    {
        $outbound = OutBoundConfirmationTicket::whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('id','DESC')->get();
        $result_operator['count_outbound'] = count($outbound);
        return new DashboardOutboundResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/confirmation-ticket/average_call_time/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="average_ca_outboundll_time_confirmation_ticket",
    *       summary="average call time outbound confirmation ticket for minutes",
    *       description="average call time outbound confirmation ticket for minutes",
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
    public function average_call_time_confirmation_ticket($start_date,$end_date)
    {
        // $outbound = Operator::whereBetween('created_at',[date($start_date), date($end_date)])->get();
        $outbound = OutBoundConfirmationTicket::whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('id','DESC')
        ->get();

        if (!count($outbound)) {
            $result = 0;
        } else {
            $count_outbound = count($outbound);
            $sumOperator = $outbound->sum('call_duration');

            $result = $sumOperator / $count_outbound;
        }
        $result_operator = [
            'average_call_time' => $result
        ];
        return new DashboardOutboundResource((object)$result_operator);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/confirmation-ticket/current_call_session_detail_information/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="current_ca_outboundll_session_detail_information_confirmation_ticket",
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
    *                    "call_time": "22/10/2023",
    *                    "call_duration": 22,
    *                    "result_call": "sdddsdds"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function current_call_session_detail_information_confirmation_ticket($start_date,$end_date,Request $request)
    {
        $outbound = OutBoundConfirmationTicket::whereDate('call_time', '>=', date($start_date))
        ->whereDate('call_time', '<=', date($end_date))->orderBy('call_time','DESC');

        $outbound = $request->get('page') == 'all' ? $outbound->get() : $outbound->paginate(10);
        return OutboundConfirmationTicketResource::collection($outbound);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/confirmation-ticket/performance_hourly_today/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="performanc_outbounde_hourly_today_confirmation_ticket",
    *       summary="performance hourly today",
    *   @OA\Response(
    *           response="200",
    *           description="Ok"
    *      ),
    *  )
    */
    public function performance_hourly_today_confirmation_ticket($start_date,$end_date)
    {
        //$data = OutBoundConfirmationTicket::whereDate('created_at', Carbon::today()->toDateString())->get()->groupBy(function($date) {
        $data = OutBoundConfirmationTicket::whereDate('call_time','>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->get()->groupBy(function($date) {
            return Carbon::parse($date->call_time)->format('H');
        });
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/count_category/{start_date}/{end_date}",
    *       tags={"Dashboard Outbound"},
    *       operationId="Outbound count_category",
    *       summary="Outbound count_category",
    *       description="Outbound count_category",
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
        $category = OutBoundConfirmationTicket::whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->groupBy('category')->selectRaw("category,
        count(*) as count_category,
        round((Count(category)* 100.0 / (
        select
            Count(*)
        from
            outbound_confirmation_ticket where date(call_time) >='".date($start_date)."' and date(call_time) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$category]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/count_status/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard outbound count_status",
    *       summary="Dashboard outbound count_status",
    *       description="Dashboard outbound count_status",
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
        $tags = OutBoundConfirmationTicket::whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->groupBy('status')->selectRaw("status,
        count(*) as count_status,
        round((Count(status)* 100.0 / (
        select
            Count(*)
        from
            outbound_confirmation_ticket where date(call_time) >='".date($start_date)."' and date(call_time) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$tags]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/count_status_mt/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard outbound count_status_mt",
    *       summary="Dashboard outbound count_status_mt",
    *       description="Dashboard outbound count_status_mt",
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
    public function count_status_mt($start_date,$end_date): JsonResponse
    {
        $tags = OutBound::whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->where('owned','outbound_ask_more')->groupBy('status')->selectRaw("status,
        count(*) as count_status_mt,
        round((Count(status)* 100.0 / (
        select
            Count(*)
        from
            OutBound where date(call_time) >='".date($start_date)."' and date(call_time) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$tags]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/count_status_lead/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard outbound count_status_lead",
    *       summary="Dashboard outbound count_status_lead",
    *       description="Dashboard outbound count_status_lead",
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
    public function count_status_lead($start_date,$end_date): JsonResponse
    {
        $tags = OutBound::whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->where('owned','outbound_leads')->groupBy('status')->selectRaw("status,
        count(*) as count_status_lead,
        round((Count(status)* 100.0 / (
        select
            Count(*)
        from
            OutBound where date(call_time) >='".date($start_date)."' and date(call_time) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$tags]);
    }

    /**
    *    @OA\Get(
    *       path="/api/dashboard/outbound/count_status_agency/{start_date}/{end_date}",
    *       tags={"Dashboard"},
    *       operationId="Dashboard outbound count_status_agency",
    *       summary="Dashboard outbound count_status_agency",
    *       description="Dashboard outbound count_status_agency",
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
    public function count_status_agency($start_date,$end_date): JsonResponse
    {
        $tags = OutBound::whereDate('call_time', '>=', date($start_date))->whereDate('call_time', '<=', date($end_date))->where('owned','outbound_agency')->groupBy('status')->selectRaw("status,
        count(*) as count_status_agency,
        round((Count(status)* 100.0 / (
        select
            Count(*)
        from
            OutBound where date(call_time) >='".date($start_date)."' and date(call_time) <='".date($end_date)."')),2) as percentage")->get();
        return response()->json(['data'=>$tags]);
    }
}