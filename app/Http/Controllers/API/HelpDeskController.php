<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HelpdeskRequest;
use App\Http\Resources\Helpdesk\HelpDeskResource;
use App\Models\HelpDesk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HelpDeskController extends Controller
{
    /**
    *    @OA\Get(
    *       path="api/helpdesk",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk",
    *       summary="read helpdesk",
    *       description="read helpdesk",
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "branch_code": "integer",
    *                   "branch_name": "string",
    *                   "branch_name_staff": "string",
    *                   "branch_phone_number": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "integer",
    *                   "result_call": "string",
    *                   "name_agent": "string",
    *                   "resulinput_voice_callt_call": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('read',HelpDesk::class);
        $data = HelpDesk::with(['Status'])->where('agent_id', Auth::user()->id)->get();
        $data['status'] = $data->Status->status;
        return HelpDeskResource::collection($data);
    }

    /**
    *    @OA\POST(
    *       path="/api/helpdesk",
    *       tags={"Helpdesk"},
    *       operationId="create helpdesk",
    *       summary="create helpdesk",
    *       description="create helpdesk",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"branch_code","branch_name","branch_name_staff","branch_phone_number","date_to_call","call_duration","result_call","name_agent","input_voice_call"},
    *                 @OA\Property(
    *                     property="branch_code",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="branch_name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="branch_name_staff",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="branch_phone_number",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="date_to_call",
    *                     type="string",
    *                     description="date_to_call must be Y-m-d H:i example 2023-10-22 10:11"
    *                 ),
    *                 @OA\Property(
    *                     property="call_duration",
    *                     type="integer",
    *                     description="please insert a minute"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="name_agent",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="input_voice_call",
    *                     type="file",
    *                     description="required | must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"branch_code": 202,"branch_name": "prod","branch_name_staff": "production","branch_phone_number": "0886622891027","date_to_call":"2023-10-22 10:11","call_duration": 16,"result_call": "anything","name_agent":"kukuh","input_voice_call":"PLEASE INPUT FILE AUDIO"}
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
    *                    "agent_id": "integer",
    *                    "branch_code": "string",
    *                    "branch_name": "string",
    *                    "branch_name_staff": "string",
    *                    "branch_phone_number": "string",
    *                    "date_to_call": "string",
    *                    "call_duration": "string",
    *                    "result_call": "string",
    *                    "name_agent": "string",
    *                    "input_voice_call": "string",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(HelpdeskRequest $helpdeskRequest)
    {
        $this->authorize('create',HelpDesk::class);
        $data = $helpdeskRequest->all();
        $filenameWithExt = $data['input_voice_call']->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $data['input_voice_call']->getClientOriginalExtension();
        $resultFilename = $filename.'_'.time().'.'.$extension;
        $helpdeskRequest->file('input_voice_call')->storeAs('public/input_voice_call',$resultFilename);

        $data['input_voice_call'] = 'public/input_voice_call/'.$resultFilename;
        $data['agent_id'] = Auth::user()->id;
        $helpDesk = HelpDesk::create($data);
        return new HelpDeskResource($helpDesk);
    }
}
