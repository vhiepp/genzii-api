<?php

namespace App\Http\Controllers;

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
//            dd($payload);
            if ($payload['exp'] >= time()) {
                $provider = $payload['firebase']['sign_in_provider'];
                $providerId = $payload['firebase']['identities'][$provider][0];

            }



//            $user = User::where('provider', $provider)
//                ->where('provider_id', $providerId)
//                ->first();
//            if (!$user) {
//                $email = explode("@", $request->input('providerData')[0]['email']);
//                $mssv = '';
//                if ($email[1] == 'st.tvu.edu.vn') {
//                    $mssv = $email[0];
//                }
//                $avatar = $request->input('providerData')[0]['photoURL']
//                    ? $request->input('providerData')[0]['photoURL']
//                    : env('APP_URL') . '/assets/images/avatars/avatar_' . rand(1, 24) . '.jpg';
//                $name = explode(" ", $request->input('providerData')[0]['displayName'], 2);
//                $user = User::create([
//                    'fullname' => $request->input('providerData')[0]['displayName'],
//                    'sur_name' => !empty($name[0]) ? $name[0] : '',
//                    'given_name' => !empty($name[1]) ? $name[1] : '',
//                    'phone' => $request->input('providerData')[0]['phoneNumber'],
//                    'email' => $request->input('providerData')[0]['email'],
//                    'stu_code' => $mssv,
//                    'provider' => $provider,
//                    'provider_id' => $providerId,
//                    'avatar' => $avatar,
//                    'role' => 'student',
//                    'password' => rand(),
//                ]);
//            }
//
//            $token = auth()->tokenById($user['id']);
//            if (!$token) {
//                return response()->json(['error' => 'Unauthorized'], 401);
//            }
//            $cookie = cookie('token', $token, auth()->factory()->getTTL());
//            return response()->json([
//                'is_valid' => true,
//                'user' => $user
//            ])->cookie($cookie);

        } catch (Exception $exception) {
            return response()->json([
                'error' => true,
                'message' => 'error'
            ]);
        }
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
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
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
