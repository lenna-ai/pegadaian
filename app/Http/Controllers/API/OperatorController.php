<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperatorRequest;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    /**
    *    @OA\Get(
    *       path="api/operator/{order_by}/{start_date}/{end_date}",
    *       tags={"Operator"},
    *       operationId="read operator",
    *       summary="read operator",
    *       description="read operator",
    *     @OA\Parameter(
    *         description="Parameter order_by examples",
    *         in="path",
    *         name="order_by",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="asc || desc", summary="An string date value."),
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
    *                   "id": "integer",
    *                   "name_agent": "integer",
    *                   "name_customer": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "string",
    *                   "result_call": "string",
    *                   "input_voice_call": "string | null",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index($order_by='desc',$start_date,$end_date): AnonymousResourceCollection
    {
        $this->authorize('read',Operator::class);
        $data = Operator::where('agent_id', Auth::user()->id)->whereDate('date_to_call', '>=', date($start_date))
        ->whereDate('date_to_call', '<=', date($end_date))->orderBy('date_to_call',$order_by)->paginate(10);
        return OperatorResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="api/operator/detail/{id}",
    *       tags={"Operator"},
    *       operationId="read operator detail",
    *       summary="read operator detail",
    *       description="read operator detail",
    *       @OA\Parameter(
    *         description="Operator ID",
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
    *                   "name_agent": "integer",
    *                   "name_customer": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "string",
    *                   "result_call": "string",
    *                   "input_voice_call": "string | null",
    *              }
    *          }),
    *      ),
    *  )
    */
    public function detail($id)
    {
        $this->authorize('read',Operator::class);
        $operator = Operator::find($id);
        return new OperatorResource($operator);
    }

    /**
    *    @OA\POST(
    *       path="/api/operator",
    *       tags={"Operator"},
    *       operationId="create operator",
    *       summary="create operator",
    *       description="create operator",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name_customer","date_to_call","call_duration","result_call","category","tag"},
    *                 @OA\Property(
    *                     property="name_agent",
    *                     type="string",
    *                     description="this agent for current user login"
    *                 ),
    *                 @OA\Property(
    *                     property="name_customer",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="date_to_call",
    *                     type="string",
    *                     description="date_to_call must be dd/mm/yyyy example 22/10/2023"
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
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="input_voice_call",
    *                     type="file",
    *                     description="must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"name_agent": "CURRENT USER","name_customer": "prod@gmail.com","date_to_call": "22/11/2023","call_duration": 22,"result_call":"anything","category":"A","tag":"A","input_voice_call":"PLEASE INPUT FILE AUDIO"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name_agent": "string",
    *                   "name_customer": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "string",
    *                   "result_call": "string",
    *                   "category": "string",
    *                   "tag": "string",
    *                   "input_voice_call": "string | null",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(OperatorRequest $operatorRequest): OperatorResource
    {
        $this->authorize('create',Operator::class);
        $data = $operatorRequest->all();

        if(!empty($data['input_voice_call']) && $data['input_voice_call'] instanceof UploadedFile) {
            $filenameWithExt = $data['input_voice_call']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $data['input_voice_call']->getClientOriginalExtension();
            $resultFilename = $filename.'_'.time().'.'.$extension;
            $operatorRequest->file('input_voice_call')->storeAs('public/input_voice_call',$resultFilename);

            $data['input_voice_call'] = 'public/input_voice_call/'.$resultFilename;
        }

        $data['agent_id'] = auth()->user()->id;
        $data['name_agent'] = auth()->user()->name;
        $operator = Operator::create($data);
        return new OperatorResource($operator);
    }

    /**
    *    @OA\POST(
    *       path="/api/operator/update/{id}",
    *       tags={"Operator"},
    *       operationId="update operator",
    *       summary="update operator",
    *       description="update operator",
    *       @OA\Parameter(
    *         description="Operator ID",
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *       ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name_customer","date_to_call","call_duration","result_call","category","tag"},
    *                 @OA\Property(
    *                     property="name_agent",
    *                     type="string",
    *                     description="this agent for current user login"
    *                 ),
    *                 @OA\Property(
    *                     property="name_customer",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="date_to_call",
    *                     type="string",
    *                     description="date_to_call must be dd/mm/yyyy example 22/10/2023"
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
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="input_voice_call",
    *                     type="file",
    *                     description="must be file | mimes:mpga,wav,m4a,wma,aac,mp3,mp4"
    *                 ),
    *                 example={"name_agent": "CURRENT USER","name_customer": "prod@gmail.com","date_to_call": "22/11/2023","call_duration": 22,"result_call":"anything","category":"A","tag":"A","input_voice_call":"PLEASE INPUT FILE AUDIO"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="201",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name_agent": "string",
    *                   "name_customer": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "string",
    *                   "result_call": "string",
    *                   "category": "string",
    *                   "tag": "string",
    *                   "input_voice_call": "string | null",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function update($id, OperatorRequest $operatorRequest): OperatorResource
    {
        $this->authorize('update',Operator::class);
        $operator = Operator::find($id);
        $data = $operatorRequest->all();

        if(!empty($data['input_voice_call']) && $data['input_voice_call'] instanceof UploadedFile) {
            $filenameWithExt = $data['input_voice_call']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $data['input_voice_call']->getClientOriginalExtension();
            $resultFilename = $filename.'_'.time().'.'.$extension;
            $operatorRequest->file('input_voice_call')->storeAs('public/input_voice_call',$resultFilename);

            $data['input_voice_call'] = 'public/input_voice_call/'.$resultFilename;

        } else if(empty($data['input_voice_call'])) {
            $data['input_voice_call'] = null;
        }

        $data['agent_id'] = auth()->user()->id;
        $data['name_agent'] = auth()->user()->name;
        $operator->update($data);
        return new OperatorResource($operator);
    }
}
