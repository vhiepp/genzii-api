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
                return response()->json(reshelper()->withFormat($this->resProfile($user)));
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }

    public function profileWithId(Request $request, string $id) {
        try {
            if (str($id)->isUuid()) {
                $user = User::find($id);
            } else {
                $user = User::where('uid', $id)->first();
            }
            if ($user) {
                return response()->json(reshelper()->withFormat($this->resProfile($user)));
            }
        } catch (\Exception $exception) {}

        return response()->json(reshelper()->withFormat(null, 'Error or not found user', 'not_found', false, true));
    }

    public function resProfile(User $user) {
        if ($user) {
            $posts_total = $user->posts()->count();
            $followers_total = $user->followers()->count();
            $following_total = $user->following()->count();
            return [
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
                'is_following' => $this->userService->isFollowingUser(auth()->user(), $user),
                'is_friend' => $this->userService->isFriend(auth()->user(), $user),
                'is_send_invitation' => !!auth()->user()->sendFriendRequests->where('id', $user->id)->first(),
                'is_request_friend' => !!$user->sendFriendRequests()->where('id', auth()->user()->id)->first()
            ];
        }
        return null;
    }

    public function searchUser(Request $request) {
        try {
            $searchKey = $request->search_key ? $request->search_key : null;
            if ($searchKey) {
                $users = $this->userService->searchUserForKey($searchKey);
                foreach ($users as $user) {
                    $followers_total = $user->followers()->count();
                    $user->is_following = $this->userService->isFollowingUser(auth()->user(), $user);
                    $user->follower = [
                        'total' => $followers_total,
                        'total_short' => numberhelper()->abbreviateNumber($followers_total),
                    ];
                }
                return response()->json(reshelper()->withFormat($users));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
