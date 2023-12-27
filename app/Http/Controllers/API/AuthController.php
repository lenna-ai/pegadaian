<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\StatusHelper;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\DataMessage\Message;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        // $this->middleware('auth:api')->except(['login','register']);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/register",
    *       tags={"Authenticate User"},
    *       operationId="Register User",
    *       summary="Register User",
    *       description="Untuk Register User",
    *    @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *            required={"name", "email", "password"},
    *            @OA\Property(property="name", type="string", format="string", example="name"),
    *            @OA\Property(property="email", type="string", format="string", example="email@gmail.com"),
    *            @OA\Property(property="roles", type="integer", format="integer", example="1"),
    *            @OA\Property(property="password", type="string", format="string", example="password"),
    *         ),
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
    *                   "access_token": "token",
    *                   "token_type": "bearer"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function register(RegisterRequest $request):ResourcesUser
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $user = User::create($data);
            $user->Role()->attach($request->roles);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // throw new Exception(response()->json([
            //     'message'=>$e->getMessage()
            // ]));
            throw new HttpException(406,$e->getMessage());
        }
        return new ResourcesUser($user);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/login",
    *       tags={"Authenticate User"},
    *       operationId="login User",
    *       summary="login User",
    *       description="Untuk login User",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),
    *                 example={"email": "arifin@lenna.ai", "password": "password"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="202",
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
    *                   "access_token": "token",
    *                   "token_type": "bearer"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function login(LoginRequest $request): ResourcesUser
    {
        $credentials = [
            'email'=>$request['email'],
            'password'=>$request['password']
        ];

        if (!$token = auth()->attempt($credentials)) {
            throw new HttpException(401,"Email atau Password salah");
            // throw new Exception("Email atau Password salah",401);
        }
        //update
        $user = User::with(['Role','Status'])->find(auth()->user()->id);
        $user->login_at = Carbon::now()->toDateTimeString();
        $user->save();
        //akhir update


        $user = Auth::user();
        $user['token'] = $token;
        $user['role'] = $user->Role;
        $user['status'] = $user->Status->status;
        $user['login_at'] = Carbon::now()->toDateTimeString();

        StatusHelper::changeStatus($user->id, 'online');
        return new ResourcesUser($user);
        // return response()->json([ 'data' => new ResourcesUser($user)]);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/me",
    *       tags={"Authenticate User"},
    *       operationId="current User",
    *       summary="current User",
    *       description="current User",
    *       @OA\Response(
    *           response="202",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "id": 10,
    *                   "name": "arifin",
    *                   "email": "ssarifin@gmails.scoma",
    *                   "email_verified_at": null,
    *                   "created_at": "2023-11-24T15:14:25.000000Z",
    *                   "updated_at": "2023-11-24T15:14:25.000000Z"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function me(): JsonResponse
    {
        $user = User::with(['Role','Status'])->find(auth()->user()->id);
        $user['status'] = $user->Status->status;
        return Message::TextMessage(['data' => $user]);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/logout",
    *       tags={"Authenticate User"},
    *       operationId="logout User",
    *       summary="logout User",
    *       description="Untuk logout User",
    *       @OA\Response(
    *           response="204",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "message": "Successfully logged out",
    *          }),
    *      ),
    *  )
    */
    public function logout(): JsonResponse
    {

        //update
        $user = User::find(auth()->user()->id);
        $user->logout_at = Carbon::now()->toDateTimeString();
        $user->save();
        //akhir update

        StatusHelper::changeStatus($user->id, 'offline');

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'],204);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/refresh",
    *       tags={"Authenticate User"},
    *       operationId="Refresh User",
    *       summary="Refresh User",
    *       description="Untuk Refresh User",
    *       @OA\Response(
    *           response="202",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *               "data": {
    *                   {
    *                   "name": "string",
    *                   "email": "string",
    *                   "access_token": "token",
    *                   "token_type": "bearer"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function refresh(): ResourcesUser
    {
        $user = auth()->user();
        $user['token'] = auth()->refresh();
        return new ResourcesUser($user);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/change-status",
    *       tags={"Authenticate User"},
    *       operationId="Change status user",
    *       summary="change status",
    *       description="Untuk mengubah status",
    *    @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="string"
    *                 ),
    *                 example={"user_id": "0", "password": "online"}
    *             )
    *         )
    *     ),
    *       @OA\Response(
    *           response="200",
    *           description="Ok",
    *           @OA\JsonContent
    *           (example={
    *             "status": true,
    *          }),
    *      ),
    *  )
    */
    public function change_status(ChangeStatusRequest $request): JsonResponse
    {
        StatusHelper::changeStatus($request->user_id, $request->status);

        return response()->json([
            'status' => true
        ]);
    }
}
