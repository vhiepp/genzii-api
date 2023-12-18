<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->userService = new UserService();
    }

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
                    'is_following' => $this->userService->isFollowingUser(auth()->user(), $user)
                ]));
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }

    public function profileWithId(Request $request, string $id) {
        try {
            $user = User::find($id);
            if ($user) {
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
                    'is_following' => $this->userService->isFollowingUser(auth()->user(), $user)
                ]));
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }
}
