<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Create a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $post = Post::create(
            array_merge(
                $request->only([
                    'title',
                    'description',
                    'content',
                ]),
                [
                    'user_id' => Auth::id(),
                ]
            )
        );
        return response()->json([
            'message' => 'Create post successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Create a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $post = Post::whereId($id)->first();

        if (!$post || ($post->user_id != Auth::id()) && !(Auth::user()->userDetail->is_admin ?? false)) {
            abort(403, 'Permission denied');
        }
        $post->fill($request->only([
            'title',
            'description',
            'content',
        ]));
        $post->save();

        return response()->json([
            'message' => 'Update post successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Create a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, int $id)
    {
        $post = Post::whereId($id)->first();

        if (!$post || ($post->user_id != Auth::id()) && !(Auth::user()->userDetail->is_admin ?? false)) {
            abort(403, 'Permission denied');
        }
        $post->delete();

        return response()->json([
            'message' => 'Delete post successfully',
        ], 200);
    }

    /**
     * Create a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = Comment::create(
            array_merge(
                $request->only([
                    'comment',
                    'post_id',
                    'content',
                ]),
                [
                    'user_id' => Auth::id(),
                ]
            )
        );
        return response()->json([
            'message' => 'Create comment successfully',
            'comment' => $comment,
        ], 200);
    }
}
