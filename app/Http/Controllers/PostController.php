<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->postService = new PostService();
    }
    public function createNewPost(Request $request)
    {
        try {
            $mediaUrl = filehelper()->saveMedia($request->media, auth()->user()->uid);
            $post = $this->postService->createNew(
                auth()->user(),
                $request->caption,
                $mediaUrl,
                $request->limit
            );
            $post = Post::find($post->id);
            return response()->json(reshelper()->withFormat($post, 'Create new post successfully'));
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
            $posts = $this->postService->getPostForUser($user);
            return response()->json(reshelper()->withFormat($posts));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function getPosts(Request $request)
    {

    }

    public function getPostWithId(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            return response()->json(reshelper()->withFormat($post));
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}
