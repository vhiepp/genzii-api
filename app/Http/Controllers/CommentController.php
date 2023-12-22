<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public UserService $userService;
    public PostService $postService;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->userService = new UserService();
        $this->postService = new PostService();
    }

    public function getCommentForPostId(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                $comments = $post->comments()->orderBy('updated_at', 'asc')->paginate(8);
                return response()->json(reshelper()->withFormat($comments));
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }

    public function createCommentForPostId(Request $request, string $id)
    {
        try {
            $content = $request->input('content') ? $request->input('content') : null;
            if ($content) {
                $post = Post::find($id);
                if ($this->postService->isUserHavePermissionToViewPost(auth()->user(), $post)) {
                    $comment = $this->postService->createComment($post, auth()->user(), $content);
                    $comment = Comment::find($comment->id);
                    return response()->json(reshelper()->withFormat($comment, 'Create comment success'));
                }
            }
        } catch (\Exception $exception) {}
        return response(reshelper()->withFormat(null, 'Error, It may be due to incorrect parameters being passed', 'error', false, true));
    }
}