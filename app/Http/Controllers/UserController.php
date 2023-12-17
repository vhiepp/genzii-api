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
                    'status' => 'success',
                    'message' => '',
                    'error' => false,
                    'profile' => $user,
                    'posts_total' => [
                        'count' => $posts_total,
                        'count_short' => numberhelper()->abbreviateNumber($posts_total),
                    ],
                    'followers_total' => [
                        'count' => $followers_total,
                        'count_short' => numberhelper()->abbreviateNumber($followers_total),
                    ],
                    'following_total' => [
                        'count' => $following_total,
                        'count_short' => numberhelper()->abbreviateNumber($following_total),
                    ],
                ]);
            }
        } catch (\Exception $exception) {}
        return response()->json([
            'status' => 'error',
            'message' => 'Error or not found user',
            'error' => true,
            'profile' => null,
            'posts_total' => null,
            'followers_total' => null,
            'following_total' => null
        ]);
    }
}
