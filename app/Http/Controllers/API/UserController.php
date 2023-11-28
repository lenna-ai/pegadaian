<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\User;
use App\Models\User as ModelsUser;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
    *    @OA\Get(
    *       path="/api/user",
    *       tags={"User"},
    *       operationId="get all User",
    *       summary="get all User",
    *       description="get all User",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="roles",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),
    *                 example={"name": "hai","email": "prod@gmail.com","roles": 1,"password": "password"}
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
    *                   "name": "string",
    *                   "email": "string",
    *                   "roles": {
    *                       {
    *                           "name": "admin",
    *                           "permissions": {
    *                               {
    *                                   "name": "create_user"
    *                               },
    *                               {
    *                                   "name": "update_user"
    *                               },
    *                               {
    *                                   "name": "read_user"
    *                               },
    *                               {
    *                                   "name": "delete_user"
    *                               }
    *                           }
    *                       }
    *                   },
    *                   "login_at": "string",
    *                   "logout_at": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function index():AnonymousResourceCollection
    {
        $this->authorize('read', User::class);
        $user = ModelsUser::all();
        return User::collection($user);
    }

    /**
    *    @OA\POST(
    *       path="/api/user",
    *       tags={"User"},
    *       operationId="create User",
    *       summary="create User",
    *       description="create User",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="roles",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),
    *                 example={"name": "hai","email": "prod@gmail.com","roles": 1,"password": "password"}
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
    *                   "name": "string",
    *                   "email": "string",
    *                   "roles": {
    *                       {
    *                           "name": "admin",
    *                           "permissions": {
    *                               {
    *                                   "name": "create_user"
    *                               },
    *                               {
    *                                   "name": "update_user"
    *                               },
    *                               {
    *                                   "name": "read_user"
    *                               },
    *                               {
    *                                   "name": "delete_user"
    *                               }
    *                           }
    *                       }
    *                   },
    *                   "login_at": "string",
    *                   "logout_at": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function create(UserRequest $request): User
    {
        $this->authorize('create', User::class);

        $data = $request->all();
        $user = new ModelsUser;
        try {
            DB::transaction(function() use($data, $request, &$user) {
                $user = ModelsUser::create($data);
                $user->Role()->attach($request->roles);
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception(response()->json([
                'message'=>$e->getMessage()
            ]));
        }
        return new User($user);
    }

    /**
    *    @OA\PUT(
    *       path="/api/user/{id}",
    *       tags={"User"},
    *       operationId="update User",
    *       summary="update User",
    *       description="update User",
    *     @OA\Parameter(
    *          name="id",
    *          description="identity",
    *          required=true,
    *          in="query",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *     ),
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="roles",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),
    *                 example={"name": "hai","email": "prod@gmail.com","roles": 1,"password": "password"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="204",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name": "string",
    *                   "email": "string",
    *                   "roles": {
    *                       {
    *                           "name": "admin",
    *                           "permissions": {
    *                               {
    *                                   "name": "create_user"
    *                               },
    *                               {
    *                                   "name": "update_user"
    *                               },
    *                               {
    *                                   "name": "read_user"
    *                               },
    *                               {
    *                                   "name": "delete_user"
    *                               }
    *                           }
    *                       }
    *                   },
    *                   "login_at": "string",
    *                   "logout_at": "string",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function update(UserRequest $request,int $id): User
    {
        $this->authorize('update', User::class);

        $data = $request->all();
        try {
            DB::beginTransaction();
            $user = ModelsUser::findOrFail($id)->update($data);
            UserRole::where('user_id',$id)->update([
                'user_id'=>$id,
                'role_id'=>$request->roles
            ]);
            DB::commit();
            $user = ModelsUser::findOrFail($id);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception(response()->json([
                'message'=>$e->getMessage()
            ]));
        }
        return new User($user);
    }

    /**
    *    @OA\DELETE(
    *       path="/api/user/{id}",
    *       tags={"User"},
    *       operationId="delete User",
    *       summary="delete User",
    *       description="delete User",
    *     @OA\Parameter(
    *          name="id",
    *          description="identity",
    *          required=true,
    *          in="query",
    *     ),
    *       @OA\Response(
    *           response="202",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name": "null",
    *                   "email": "null",
    *                   "roles": {},
    *                   "login_at": "null",
    *                   "logout_at": "null",
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function delete(int $id): User
    {
        $this->authorize('delete', User::class);

        try {
            DB::beginTransaction();
            ModelsUser::findOrFail($id)->delete();
            UserRole::where('user_id',$id)->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception(response()->json([
                'message'=>$e->getMessage()
            ]));
        }
        $user = new ModelsUser;
        return new User($user);
    }
}
