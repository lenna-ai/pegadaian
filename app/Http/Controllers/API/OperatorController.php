<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperatorRequest;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    /**
    *    @OA\Get(
    *       path="api/operator",
    *       tags={"Operator"},
    *       operationId="read operator",
    *       summary="read operator",
    *       description="read operator",
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name_agent": "integer",
    *                   "name_customer": "string",
    *                   "date_to_call": "string",
    *                   "call_duration": "string",
    *                   "result_call": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('read',Operator::class);
        $data = Operator::where('agent_id', Auth::user()->id)->get();
        return OperatorResource::collection($data);
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
    *               required={"name_customer","date_to_call","call_duration","result_call"},
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
    *                 example={"name_agent": "CURRENT USER","name_customer": "prod@gmail.com","date_to_call": "22/11/2023","call_duration": 22,"result_call":"anything"}
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
        $data['name_agent'] = auth()->user()->name;
        $operator = Operator::create($data);
        return new OperatorResource($operator);
    }
}
