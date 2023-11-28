<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperatorRequest;
use App\Http\Resources\Operator\OperatorResource;
use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index(): void
    {

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
    *               required={"name_agent","name_customer","date_to_call","call_duration","result_call"},
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
    *                     property="call_duration",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="result_call",
    *                     type="string"
    *                 ),
    *                 example={"name_agent": "hai","name_customer": "prod@gmail.com","date_to_call": "22/11/2023","call_duration": 22,"result_call":"anything"}
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
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(OperatorRequest $operatorRequest): OperatorResource
    {
        $data = $operatorRequest->all();
        $operator = Operator::create($data);
        return new OperatorResource($operator);
    }
}
