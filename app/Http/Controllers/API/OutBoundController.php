<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutboundConfirmationTicketRequest;
use App\Http\Requests\OutboundRequest;
use App\Http\Resources\Outbound\OutboundConfirmationTicketResource;
use App\Http\Resources\Outbound\OutboundResource;
use App\Models\OutBound;
use App\Models\OutBoundConfirmationTicket;
use App\Models\StatusTrack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OutBoundController extends Controller
{
    /**
    *    @OA\Get(
    *       path="/api/outbound/{page}/outlet/statusTrack",
    *       tags={"Outbound"},
    *       operationId="read outbound outlet statusTrack by page",
    *       summary="read outbound outlet statusTrack by page",
    *       description="read outbound outlet statusTrack by page",
    *       @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads , confirmation_ticket)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *      ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function statusTrack(string $page)
    {
        $data = StatusTrack::where('owned', 'outbound_' . $page)->get(['name']);
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\POST(
    *       path="/api/outbound/{page}",
    *       tags={"Outbound"},
    *       operationId="create outbound by page",
    *       summary="create outbound by page",
    *       description="create outbound by page",
    *    @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *    ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name","call_time","call_duration","status"},
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="call_time",
    *                     type="string",
    *                     description="call_time must be Y-m-d H:i example 2023-10-22 10:11"
    *                 ),
    *                 @OA\Property(
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="string",
    *                 ),
    *                 example={"name": "Randika Test","call_time": "2023-10-22 10:11","call_duration": 45,"status": "DIANGKAT"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "data": {
    *                {
    *                    "id": "integer",
    *                    "name": "string",
    *                    "call_time": "string",
    *                    "call_duration": "integer",
    *                    "status": "string",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(string $page, OutboundRequest $request)
    {
        $this->authorize('create',Outbound::class);
        $data = $request->all();
        $data['agent_id'] = Auth::user()->id;
        $data['owned'] = 'outbound_' . $page;

        $create = OutBound::create($data);
        return new OutboundResource($create);
    }

    /**
    *    @OA\POST(
    *       path="/api/outbound/{page}/update/{id}",
    *       tags={"Outbound"},
    *       operationId="update outbound",
    *       summary="update outbound",
    *       description="update outbound",
    *    @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *    ),
    *    @OA\Parameter(
    *         description="Outbound ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *    ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name","call_time","call_duration","status"},
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="call_time",
    *                     type="string",
    *                     description="call_time must be Y-m-d H:i example 2023-10-22 10:11"
    *                 ),
    *                 @OA\Property(
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="string",
    *                 ),
    *                 example={"name": "Randika Test","call_time": "2023-10-22 10:11","call_duration": 45,"status": "DIANGKAT"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "data": {
    *                {
    *                    "id": "integer",
    *                    "name": "string",
    *                    "call_time": "string",
    *                    "call_duration": "integer",
    *                    "status": "string",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function update(string $page, OutBound $outbound, OutboundRequest $request)
    {
        $this->authorize('create',Outbound::class);
        $data = $request->all();
        $data['agent_id'] = Auth::user()->id;
        $data['owned'] = 'outbound_' . $page;

        $outbound->update($data);
        return new OutboundResource($outbound);
    }

    /**
    *    @OA\Get(
    *       path="/api/outbound/{page}",
    *       tags={"Outbound"},
    *       operationId="read outbound by page",
    *       summary="read outbound by page",
    *       description="read outbound by page",
    *       @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *      ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "id": "integer",
    *                   "name": "string",
    *                   "call_time": "string",
    *                   "call_duration": "string",
    *                   "status": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index(string $page,Request $request)
    {
        $this->authorize('read',Outbound::class);
        if($request->orderby == null || $request->orderby == ''){
            $OrderBy = 'desc';
        } else {
            $OrderBy = strtolower($request->get('orderby'));
        }
        $data = OutBound::where('owned', 'outbound_' . $page)->where('agent_id', Auth::user()->id)->orderBy('call_time',$OrderBy)->paginate(10);
        return OutboundResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="/api/outbound/{page}/detail/{id}",
    *       tags={"Outbound"},
    *       operationId="read outbound detail",
    *       summary="read outbound detail",
    *       description="read outbound detail",
    *       @OA\Parameter(
    *         description="Must be in list (agency , ask_more , leads)",
    *         in="path",
    *         name="page",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="page", value="agency", summary="Page value."),
    *      ),
    *       @OA\Parameter(
    *         description="Outbound ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *       ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   "id": "integer",
    *                   "name": "string",
    *                   "call_time": "string",
    *                   "call_duration": "string",
    *                   "status": "string",
    *              }
    *          }),
    *      ),
    *  )
    */
    public function detail(string $page, OutBound $outbound)
    {
        $this->authorize('read',Outbound::class);
        return new OutboundResource($outbound);
    }

    /**
    *    @OA\POST(
    *       path="/api/outbound/confirmation-ticket",
    *       tags={"Outbound"},
    *       operationId="create outbound confirmation ticket",
    *       summary="create outbound confirmation ticket",
    *       description="create outbound confirmation ticket",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name_agent","ticket_number","category","status","call_time","call_duration","result_call"},
    *                 @OA\Property(
    *                     property="name_agent",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="ticket_number",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="category",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="string",
    *                     description="must being exist in status api",
    *                 ),
    *                 @OA\Property(
    *                     property="call_time",
    *                     type="string",
    *                     description="call_time must be Y-m-d H:i example 2023-10-22 10:11"
    *                 ),
    *                 @OA\Property(
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string",
    *                 ),
    *                 example={"name_agent": "Randika Test","ticket_number": "TICKET-1234","category": "Wallet","status": "Pelapor Belum Bisa Cek / Konfirmasi Penyelesaian","call_time": "2023-10-22 10:11","call_duration": 45,"result_call": "OKE"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "data": {
    *                {
    *                    "name_agent": "string",
    *                    "ticket_number": "string",
    *                    "category": "string",
    *                    "status": "string",
    *                    "call_time": "string",
    *                    "call_duration": "integer",
    *                    "result_call": "string",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function createConfirmationTicket(OutboundConfirmationTicketRequest $request)
    {
        $this->authorize('create',OutBoundConfirmationTicket::class);
        $data = $request->all();
        $data['agent_id'] = Auth::user()->id;

        $create = OutBoundConfirmationTicket::create($data);
        return new OutboundConfirmationTicketResource($create);
    }

    /**
    *    @OA\Get(
    *       path="/api/outbound/confirmation-ticket",
    *       tags={"Outbound"},
    *       operationId="read outbound confirmation ticket",
    *       summary="read outbound confirmation ticket",
    *       description="read outbound confirmation ticket",
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   "name_agent": "string",
    *                   "ticket_number": "string",
    *                   "category": "string",
    *                   "status": "string",
    *                   "call_time": "string",
    *                   "call_duration": "integer",
    *                   "result_call": "string",
    *              }
    *          }),
    *      ),
    *  )
    */
    public function confirmationTicket()
    {
        $this->authorize('read',Outbound::class);
        $data = OutBoundConfirmationTicket::where('agent_id', Auth::user()->id)->paginate(10);
        return OutboundConfirmationTicketResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="/api/outbound/confirmation-ticket/detail/{id}",
    *       tags={"Outbound"},
    *       operationId="read outbound confirmation ticket detail",
    *       summary="read outbound confirmation ticket detail",
    *       description="read outbound confirmation ticket detail",
    *       @OA\Parameter(
    *         description="Outbound ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *       ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   "id": "integer",
    *                   "name_agent": "string",
    *                   "ticket_number": "string",
    *                   "category": "string",
    *                   "status": "string",
    *                   "call_time": "string",
    *                   "call_duration": "integer",
    *                   "result_call": "string",
    *              }
    *          }),
    *      ),
    *  )
    */
    public function detailConfirmationTicket(OutBoundConfirmationTicket $outbound)
    {
        $this->authorize('read',Outbound::class);
        return new OutboundConfirmationTicketResource($outbound);
    }

    /**
    *    @OA\POST(
    *       path="/api/outbound/confirmation-ticket/update/{id}",
    *       tags={"Outbound"},
    *       operationId="update outbound confirmation ticket",
    *       summary="update outbound confirmation ticket",
    *       description="update outbound confirmation ticket",
    *       @OA\Parameter(
    *         description="Outbound ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *       ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name_agent","ticket_number","category","status","call_time","call_duration","result_call"},
    *                 @OA\Property(
    *                     property="name_agent",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="ticket_number",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="category",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="string",
    *                     description="must being exist in status api",
    *                 ),
    *                 @OA\Property(
    *                     property="call_time",
    *                     type="string",
    *                     description="call_time must be Y-m-d H:i example 2023-10-22 10:11"
    *                 ),
    *                 @OA\Property(
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string",
    *                 ),
    *                 example={"name_agent": "Randika Test","ticket_number": "TICKET-1234","category": "Wallet","status": "Pelapor Belum Bisa Cek / Konfirmasi Penyelesaian","call_time": "2023-10-22 10:11","call_duration": 45,"result_call": "OKE"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "data": {
    *                 "id": "integer",
    *                 "name_agent": "string",
    *                 "ticket_number": "string",
    *                 "category": "string",
    *                 "status": "string",
    *                 "call_time": "string",
    *                 "call_duration": "integer",
    *                 "result_call": "string",
    *              }
    *          }),
    *      ),
    *  )
    */
    public function updateConfirmationTicket(OutBoundConfirmationTicket $outbound, OutboundConfirmationTicketRequest $request)
    {
        $this->authorize('create',OutBoundConfirmationTicket::class);
        $data = $request->all();
        $data['agent_id'] = Auth::user()->id;

        $outbound->update($data);
        return new OutboundConfirmationTicketResource($outbound);
    }
}