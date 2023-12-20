<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HelpdeskRequest;
use App\Http\Resources\Helpdesk\HelpDeskResource;
use App\Models\Category;
use App\Models\HelpDesk;
use App\Models\HelpDeskOutlet;
use App\Models\Operator;
use App\Models\StatusTrack;
use App\Models\Tag;
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
        $data = HelpDesk::where('agent_id', Auth::user()->id)->get();
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
    *               required={"branch_code","branch_name","branch_name_staff","branch_phone_number","date_to_call","call_duration","result_call","name_agent","input_voice_call","status","parent_branch","category","tag"},
    *                 @OA\Property(
    *                     property="branch_code",
    *                     type="integer",
    *                     description="must being exist in branch_code api"
    *                 ),
    *                 @OA\Property(
    *                     property="branch_name",
    *                     type="string",
    *                     description="must being exist in branch_name api"
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
    *                     property="status",
    *                     type="string",
    *                     description="must being exist in status api"
    *                 ),
    *                 @OA\Property(
    *                     property="parent_branch",
    *                     type="string",
    *                     description="must being exist in parent_branch api"
    *                 ),
    *                 @OA\Property(
    *                     property="category",
    *                     type="string",
    *                     description="must being exist in category api"
    *                 ),
    *                 @OA\Property(
    *                     property="tag",
    *                     type="string",
    *                     description="must being exist in tag api"
    *                 ),
    *                 @OA\Property(
    *                     property="input_voice_call",
    *                     type="file",
    *                     description="required | must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"branch_code": 202,"branch_name": "prod","branch_name_staff": "production","branch_phone_number": "0886622891027","date_to_call":"2023-10-22 10:11","call_duration": 16,"status": "UPC","name_agent":"kukuh","result_call": "anything","parent_branch":"CP MEDAN UTAMA","category": "A","tag":"B","input_voice_call":"PLEASE INPUT FILE AUDIO"}
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
    *                    "category": "string",
    *                    "tag": "string",
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

    /**
    *    @OA\Get(
    *       path="api/helpdesk/outlet/statusTrack",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk outlet statusTrack",
    *       summary="read statusTrack helpdesk",
    *       description="read helpdesk outlet statusTrack",
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
    public function statusTrack()
    {
        $data = StatusTrack::all();
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="api/helpdesk/outlet/parent_branch",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk outlet parent_branch",
    *       summary="read helpdesk",
    *       description="read helpdesk outlet parent_branch",
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "parent_branch": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function parent_branch()
    {
        $data = HelpDeskOutlet::distinct()->get(['parent_branch']);
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="api/helpdesk/outlet/outlet_name",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk outlet outlet_name",
    *       summary="read helpdesk",
    *       description="read helpdesk outlet outlet_name",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"parent_branch"},
    *                 @OA\Property(
    *                     property="parent_branch",
    *                     type="string"
    *                 ),
    *                 example={"status": "UPC","parent_branch": "CP MEDAN UTAMA"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "outlet_name": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function outlet_name($parent_branch)
    {
        $data = HelpDeskOutlet::distinct()->where('parent_branch', $parent_branch)->get(['outlet_name']);
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="api/helpdesk/outlet/branch_code",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk outlet branch_code",
    *       summary="read helpdesk",
    *       description="read helpdesk outlet branch_code",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"parent_branch","branch_code"},
    *                 @OA\Property(
    *                     property="parent_branch",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="outlet_name",
    *                     type="string"
    *                 ),
    *                 example={"status": "UPC","parent_branch": "CP MEDAN UTAMA","outlet_name":"UPC GAJAHMADA"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "branch_code": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function branch_code($parent_branch,$outlet_name)
    {
        $data = HelpDeskOutlet::distinct()->where([
            ['parent_branch', $parent_branch],
            ['outlet_name', $outlet_name]
        ])->get(['branch_code']);
        return response()->json(['data'=>$data]);
    }

    /**
    *    @OA\Get(
    *       path="api/outlet/tag",
    *       tags={"Outlet"},
    *       operationId="read tag",
    *       summary="read tag",
    *       description="read tag",
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
    public function tag()
    {
        $data = Tag::all();
        return response()->json(['data'=>$data]);
    }
}
