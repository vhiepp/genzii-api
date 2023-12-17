<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request) {

        try {
            $user = null;
            if ($request->id) {
                $user = User::where('id', $request->id)->first();
            }
            elseif ($request->uid) {
                $user = User::where('uid', $request->uid)->first();
            }
            elseif ($request->email) {
                $user = User::where('email', $request->email)->first();
            }
            if ($user) {
                $posts_total = $user->posts()->count();
                $followers_total = $user->followers()->count();
                $following_total = $user->following()->count();
                return response()->json([
                    'is_valid' => true,
                    'status' => 'success',
                    'message' => '',
                    'error' => false,
                    'data' => [
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
                    ]
                ]);
            }
        } catch (\Exception $exception) {}
        return response()->json([
            'is_valid' => false,
            'status' => 'not_found',
            'message' => 'Error or not found user',
            'error' => true,
            'data' => null,
        ]);
    }
}
