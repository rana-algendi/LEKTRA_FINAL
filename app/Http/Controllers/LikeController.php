<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Config;



class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor-api,ChildParent-api');
    }
    // like or unlike
    public function likeOrUnlike(Request $request)
    {
        
        $post = Post::find($request->post_id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        $like = $post->likes()->where('child_parent_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'post_id' => $request->post_id,
                'child_parent_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ], 200);
        }
        // else dislike it
        $like->delete();

        return response([
            'message' => 'Disliked'
        ], 200);
    }





    ////////doctor//////

    // like or unlike
    public function likeOrUnlike_1(Request $request)
    {
        
        $post = Post::find($request->post_id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        $like = $post->likes()->where('doctor_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'post_id' => $request->post_id,
                'doctor_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ], 200);
        }
        // else dislike it
        $like->delete();

        return response([
            'message' => 'Disliked'
        ], 200);
    }
}
