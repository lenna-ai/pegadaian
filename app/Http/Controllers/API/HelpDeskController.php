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
use Illuminate\Http\UploadedFile;
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
    *                   "id": "integer",
    *                   "ticket_number": "string",
    *                   "branch_code": "integer",
    *                   "branch_name": "string",
    *                   "branch_name_staff": "string",
    *                   "branch_phone_number": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "integer",
    *                   "result_call": "string",
    *                   "name_agent": "string",
    *                   "input_voice_callt_call": "string | null",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('read',HelpDesk::class);
        $data = HelpDesk::where('agent_id', Auth::user()->id)->paginate(10);
        return HelpDeskResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="api/helpdesk/detail/{id}",
    *       tags={"Helpdesk"},
    *       operationId="read helpdesk detail",
    *       summary="read helpdesk detail",
    *       description="read helpdesk detail",
    *       @OA\Parameter(
    *         description="Helpdesk ID",
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
    *                   "id": "string",
    *                   "ticket_number": "string",
    *                   "branch_code": "integer",
    *                   "branch_name": "string",
    *                   "branch_name_staff": "string",
    *                   "branch_phone_number": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "integer",
    *                   "result_call": "string",
    *                   "name_agent": "string",
    *                   "input_voice_callt_call": "string | null",
    *               }
    *          }),
    *      ),
    *  )
    */
    public function detail(HelpDesk $helpdesk)
    {
        $this->authorize('read',HelpDesk::class);
        return new HelpDeskResource($helpdesk);
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
    *               required={"ticket_number","branch_code","branch_name","branch_name_staff","branch_phone_number","date_to_call","call_duration","result_call","name_agent","status","parent_branch","category","tag"},
    *                 @OA\Property(
    *                     property="ticket_number",
    *                     type="string",
    *                     description="string with 'ARIA' prefix"
    *                 ),
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
    *                     description="must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"ticket_number": "ARIA-123232","branch_code": 202,"branch_name": "prod","branch_name_staff": "production","branch_phone_number": "0886622891027","date_to_call":"2023-10-22 10:11","call_duration": 16,"status": "UPC","name_agent":"kukuh","result_call": "anything","parent_branch":"CP MEDAN UTAMA","category": "A","tag":"B","input_voice_call":"PLEASE INPUT FILE AUDIO"}
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
    *                    "agent_id": "integer",
    *                    "ticket_number": "string",
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
    *                    "input_voice_call": "string | null",
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

        if(!empty($data['input_voice_call']) && $data['input_voice_call'] instanceof UploadedFile) {
            $filenameWithExt = $data['input_voice_call']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $data['input_voice_call']->getClientOriginalExtension();
            $resultFilename = $filename.'_'.time().'.'.$extension;
            $helpdeskRequest->file('input_voice_call')->storeAs('public/input_voice_call',$resultFilename);

            $data['input_voice_call'] = 'public/input_voice_call/'.$resultFilename;
        }

        $data['agent_id'] = Auth::user()->id;
        $helpDesk = HelpDesk::create($data);
        return new HelpDeskResource($helpDesk);
    }

    /**
    *    @OA\POST(
    *       path="/api/helpdesk/update/{id}",
    *       tags={"Helpdesk"},
    *       operationId="update helpdesk",
    *       summary="update helpdesk",
    *       description="update helpdesk",
    *       @OA\Parameter(
    *         description="Helpdesk ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *       ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"ticket_number","branch_code","branch_name","branch_name_staff","branch_phone_number","date_to_call","call_duration","result_call","name_agent","status","parent_branch","category","tag"},
    *                 @OA\Property(
    *                     property="ticket_number",
    *                     type="string",
    *                     description="string with 'ARIA' prefix"
    *                 ),
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
    *                     description="must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"ticket_number": "ARIA-123232","branch_code": 202,"branch_name": "prod","branch_name_staff": "production","branch_phone_number": "0886622891027","date_to_call":"2023-10-22 10:11","call_duration": 16,"status": "UPC","name_agent":"kukuh","result_call": "anything","parent_branch":"CP MEDAN UTAMA","category": "A","tag":"B","input_voice_call":"PLEASE INPUT FILE AUDIO"}
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
    *                    "agent_id": "integer",
    *                    "ticket_number": "string",
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
    *                    "input_voice_call": "string | null",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function update(HelpDesk $helpdesk, HelpdeskRequest $helpdeskRequest)
    {
        $this->authorize('update',HelpDesk::class);
        $data = $helpdeskRequest->all();

        if(!empty($data['input_voice_call']) && $data['input_voice_call'] instanceof UploadedFile) {
            $filenameWithExt = $data['input_voice_call']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $data['input_voice_call']->getClientOriginalExtension();
            $resultFilename = $filename.'_'.time().'.'.$extension;
            $helpdeskRequest->file('input_voice_call')->storeAs('public/input_voice_call',$resultFilename);

            $data['input_voice_call'] = 'public/input_voice_call/'.$resultFilename;

        } else if(empty($data['input_voice_call'])) {
            if($helpdesk->input_voice_call) {
                $filePath = storage_path('app/' . $helpdesk->input_voice_call);
                if(file_exists($filePath)) @unlink($filePath);
            }

            $data['input_voice_call'] = null;
        }

        $data['agent_id'] = Auth::user()->id;
        $helpdesk->update($data);
        return new HelpDeskResource($helpdesk);
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
    public function outlet_name(Request $request)
    {
        $request->validate([
            'parent_branch' => 'required',
        ]);
        $data = HelpDeskOutlet::distinct()->where('parent_branch', $request->parent_branch)->get(['outlet_name']);
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
    public function branch_code(Request $request)
    {
        $request->validate([
            'parent_branch' => 'required',
            'outlet_name' => 'required',
        ]);
        $data = HelpDeskOutlet::distinct()->where([
            ['parent_branch', $request->parent_branch],
            ['outlet_name', $request->outlet_name]
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
