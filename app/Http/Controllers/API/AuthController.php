<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\DataMessage\Message;
use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/register",
    *       tags={"Register User"},
    *       operationId="Register User",
    *       summary="Register User",
    *       description="Untuk Register User",
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
    *                     property="password",
    *                     type="string"
    *                 ),
    *                 example={"name": "arifin", "email": "arifin@lenna.ai", "password": "password"}
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
    *                   "access_token": "token",
    *                   "token_type": "bearer"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $user = User::create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(response()->json([
                'message'=>$e->getMessage()
            ]));
        }
        return new ResourcesUser($user);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/login",
    *       tags={"login User"},
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
    *                   "access_token": "token",
    *                   "token_type": "bearer"
    *                  }
    *              }
    *          }),
    *      ),
    *  )
    */
    public function login(Request $request): ResourcesUser
    {
        $credentials = $request->all();

        if (!$token = auth()->attempt($credentials)) {
            throw new Exception(Message::TextMessage(['error' => 'Unauthorized'], 401));
        }
        $user = auth()->user();
        $user['token'] = $token;

        return new ResourcesUser($user);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/me",
    *       tags={"current User"},
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
        return Message::TextMessage(['data' => auth()->user(),202]);
    }

    /**
    *    @OA\Post(
    *       path="/api/auth/logout",
    *       tags={"logout User"},
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
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'],204);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
