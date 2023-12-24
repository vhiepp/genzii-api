<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['signInWithEmailPassword', 'signInWithFirebase']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signInWithEmailPassword(Request $request)
    {
            $account = Account::whereProvider([
                'provider' => 'email/password',
                'provider_id' => $request->email,
                'username' => $request->email,
            ])->first();
            if ($account && Hash::check($request->password, $account->password)) {
                $user = $account->user;

                $token = auth()->tokenById($user->id);
                if ($token) {
                    $cookie = cookie('token', $token, auth()->factory()->getTTL());
                    $posts_total = $user->posts()->count();
                    $followers_total = $user->followers()->count();
                    $following_total = $user->following()->count();
                    return response()->json(reshelper()->withFormat([
                        'profile' => $user,
                        'posts' => [
                            'total' => $posts_total,
                            'total_short' => numberhelper()->abbreviateNumber($posts_total),
                        ],
                        'followers' => [
                            'total' => $followers_total,
                            'total_short' => numberhelper()->abbreviateNumber($followers_total),
                        ],
                        'following' => [
                            'total' => $following_total,
                            'total_short' => numberhelper()->abbreviateNumber($following_total),
                        ],
                    ]))->cookie($cookie);
                }
            }
        try {
        } catch (Exception $exception) { }

        return response()->json(reshelper()->withFormat(null, 'Error, could be due to wrong email or password', 'error', false, true));
    }

    public function signInWithFirebase(Request $request)
    {
        try {
            $token = explode('.', $request->firebase_access_token);
            $encoded_payload = $token[1];
            $decoded_payload = base64_decode($encoded_payload);
            $payload = json_decode($decoded_payload, true);
            if ((env('APP_ENV') == 'production' && $payload['exp'] >= time()) || env('APP_ENV') == 'local') {
                $provider = $payload['firebase']['sign_in_provider'];
                $providerId = $payload['firebase']['identities'][$provider][0];
                $account = Account::whereProvider([
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'username' => $provider . '-' . $payload['email']
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
                        'username' => $provider . '-' . $payload['email'],
                        'password' => rand() . env('JWT_SECRET', '.') . rand(),
                        'provider' => $provider,
                        'provider_id' => $providerId
                    ]);
                }
            }
            $user = User::find($user->id);
            $token = auth()->tokenById($user->id);
            if (!$token) {
                return response()->json(reshelper()->withFormat(null, 'Unauthorized', 'error', false, true));
            }
            $cookie = cookie('token', $token, auth()->factory()->getTTL());
            $posts_total = $user->posts()->count();
            $followers_total = $user->followers()->count();
            $following_total = $user->following()->count();
            return response()->json(reshelper()->withFormat([
                'profile' => $user,
                'posts' => [
                    'total' => $posts_total,
                    'total_short' => numberhelper()->abbreviateNumber($posts_total),
                ],
                'followers' => [
                    'total' => $followers_total,
                    'total_short' => numberhelper()->abbreviateNumber($followers_total),
                ],
                'following' => [
                    'total' => $following_total,
                    'total_short' => numberhelper()->abbreviateNumber($following_total),
                ],
            ], 'Successfully sign in'))->cookie($cookie);
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'It could be due to expired firebase_access_token or input parameter error', 'error', false, true));
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $cookie = cookie('token', auth()->refresh(), auth()->factory()->getTTL());
        $user = auth()->user();
        $posts_total = $user->posts()->count();
        $followers_total = $user->followers()->count();
        $following_total = $user->following()->count();
        return response()->json(reshelper()->withFormat([
            'profile' => $user,
            'posts' => [
                'total' => $posts_total,
                'total_short' => numberhelper()->abbreviateNumber($posts_total),
            ],
            'followers' => [
                'total' => $followers_total,
                'total_short' => numberhelper()->abbreviateNumber($followers_total),
            ],
            'following' => [
                'total' => $following_total,
                'total_short' => numberhelper()->abbreviateNumber($following_total),
            ],
        ]))->cookie($cookie);
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
        return response()->json(reshelper()->withFormat(null, 'Successfully sign out', 'success', true, false))->cookie($cookie);
    }

}
