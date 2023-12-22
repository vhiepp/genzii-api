<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;
    protected UserService $userService;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getPostWithId']]);
        $this->postService = new PostService();
        $this->userService = new UserService();
    }
    public function createNewPost(Request $request)
    {
        try {
            $mediaUrl = filehelper()->saveMedia($request->file('media'), auth()->user()->uid);
            $post = $this->postService->createNew(
                auth()->user(),
                $request->caption,
                $mediaUrl,
                $request->limit
            );
            $post = Post::find($post->id);
            if ($post) {
                return response()->json(reshelper()->withFormat($post, 'Create new post successfully'));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPostForUserId(Request $request, string $id)
    {
        try {
            if (str($id)->isUuid()) {
                $user = User::find($id);
            } else {
                $user = User::where('uid', $id)->first();
            }
            if ($user) {
                $posts = $this->postService->getPostForUser($user);
                return response()->json(reshelper()->withFormat($posts));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPosts(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user) {
                $notInPostIds = [];
                $newExceptPosts = [];
                $nowTime = time();
                $exceptPosts = $request->except_posts ? $request->except_posts : [];
                if ($exceptPosts && str($exceptPosts)->isJson()) {
                    $exceptPosts = json_decode($exceptPosts);
                    foreach ($exceptPosts as $exceptPost) {
                        if ($exceptPost->exp > $nowTime) {
                            array_push($notInPostIds, $exceptPost->id);
                            array_push($newExceptPosts, $exceptPost);
                        }
                    }
                }
                $posts = $this->postService->getPosts($user, 8, $notInPostIds);
                $postRes = [];
                $count = 0;
                foreach ($posts as $post) {
                    if ($count < 8 && ($count == 0 || rand(0, 1) > 0)) {
                        array_push($newExceptPosts, [
                            'id' => $post->id,
                            'exp' => $nowTime + 300
                        ]);
                        if ($post->limit == 'all' ||
                            ($post->limit == 'friends' && $this->userService->isFriend($post->author, $user)) ||
                            ($post->limit == 'only_me' && $post->author->id == $user->id))
                        {
                            $count++;
                            array_push($postRes, $post);
                        }
                    }
                    if ($count >= 8) break;
                }
                shuffle($postRes);
                return response()->json(reshelper()->withFormat([
                    'posts' => $postRes,
                    'except_posts' => json_encode($newExceptPosts)
                ]));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPostWithId(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if ($post &&
                ($post->limit == 'all' ||
                    ($post->limit == 'friends' && $this->userService->isFriend(auth()->user(), $post->author)) ||
                    ($post->limit == 'only_me' && $post->author->id == auth()->user()->id)
                )
            ) {
                return response()->json(reshelper()->withFormat($post));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
