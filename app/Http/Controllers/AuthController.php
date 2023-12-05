<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signInWithFirebase']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signInWithFirebase(Request $request)
    {
        try {
            $token = explode('.', $request->access_token);
            $encoded_payload = $token[1];
            $decoded_payload = base64_decode($encoded_payload);
            $payload = json_decode($decoded_payload, true);
//            if ($payload['exp'] >= time()) {
                $provider = $payload['firebase']['sign_in_provider'];
                $providerId = $payload['firebase']['identities'][$provider][0];

                $account = Account::whereProvider([
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'username' => $payload['email']
                ])->first();

                if ($account) {
                    $user = $account->user;
                } else {
                    $user = User::where('email', $payload['email'])->first();
                    if ($user == null) {
                        $user = User::create([
                            'full_name' => $payload['name'],
                            'firstname' => $payload['name'],
                            'lastname' => '',
                            'email' => $payload['email']
                        ]);
                    }
                    $user->accounts()->create([
                        'username' => $payload['email'],
                        'password' => rand() . env('JWT_SECRET', '.') . rand(),
                        'provider' => $provider,
                        'provider_id' => $providerId
                    ]);
                }
//            }
            $token = auth()->tokenById($user->id);
            if (!$token) {
                return response()->json([
                    'is_valid' => false,
                    'error' => true,
                    'message' => 'Unauthorized',
                    'auth' => null
                ], 401);
            }
            $cookie = cookie('token', $token, auth()->factory()->getTTL());
            return response()->json([
                'is_valid' => true,
                'error' => false,
                'message' => '',
                'auth' => [
                    'user' => $user,
                    'access_token' => $token,
                    'expires_in' => time() + (auth()->factory()->getTTL() * 60)
                ]
            ])->cookie($cookie);
        } catch (\Exception $exception) {}
        return response()->json([
            'is_valid' => false,
            'error' => true,
            'message' => 'error',
            'auth' => null
        ]);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        auth()->logout();
        $cookie = cookie()->forget('token');
        return response()->json([
            'message' => 'Successfully logged out',
            'error' => false,
            'status' => 'success'
        ], 200)->cookie($cookie);
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
