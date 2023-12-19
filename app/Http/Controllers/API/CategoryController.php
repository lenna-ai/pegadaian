<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
    *    @OA\Get(
    *       path="api/outlet/category/helpdesk",
    *       tags={"Outlet"},
    *       operationId="read category helpdesk",
    *       summary="read category helpdesk",
    *       description="read category helpdesk",
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
    public function helpdesk()
    {
        $data = Category::where('owned','helpdesk')->orderBy('id','asc')->get(['name']);
        return CategoryResource::collection($data);
    }

    /**
    *    @OA\Get(
    *       path="api/outlet/category/operator",
    *       tags={"Outlet"},
    *       operationId="read category operator",
    *       summary="read category operator",
    *       description="read category operator",
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
    public function operator()
    {
        $data = Category::where('owned','operator')->orderBy('id','asc')->get(['name']);
        return CategoryResource::collection($data);
    }

    /**
    *    @OA\POST(
    *       path="/api/category",
    *       tags={"category"},
    *       operationId="create category",
    *       summary="create category",
    *       description="create category",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               required={"name","owned"},
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="owned",
    *                     type="string",
    *                     description="required | must be roles like a operator or helpdesk"
    *                 ),
    *                 example={"name": "pocong","owned":"helpdesk"}
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
    *                    "name": "string",
    *                    "owned": "string",
    *               }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(CategoryRequest $categoryRequest)
    {
        $data = $categoryRequest->all();
        $result = Category::create($data);
        return new CategoryResource($result);
    }
}
