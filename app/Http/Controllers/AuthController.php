<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
    * @OA\Post(
    *     path="/login",
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
    * @OA\Post(
    *     path="/logout",
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
    */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

  /**
    * @OA\Post(
    *     path="/refresh",
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
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
            'access' => $token,
            'refresh' => $this->respondWithToken(auth()->refresh()),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}