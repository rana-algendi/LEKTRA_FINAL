<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Config;




class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:doctor-api,ChildParent-api');
    }
    // get all comments of a post
    public function index($id)
    {
        
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'comments' => $post->comments()->with('child_parent:id,name,image')->get()
        ], 200);
    }

    // create a comment
    public function store(Request $request)
    {
        $post = Post::find($request->post_id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $request->post_id,
            'child_parent_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Comment created.'
        ], 200);
    }

    // update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->child_parent_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }
        

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'Comment updated.'
        ], 200);
    }

    // delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->child_parent_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted.'
        ], 200);
    }

    ///////doctor///////

     // get all comments of a post
     public function index_1($id)
     {
         
         $post = Post::find($id);
 
         if(!$post)
         {
             return response([
                 'message' => 'Post not found.'
             ], 403);
         }
 
         return response([
             'comments' => $post->comments()->with('doctor:id,name,image')->get()
         ], 200);
     }
 
     // create a comment
     public function store_1(Request $request)
     {
         $post = Post::find($request->post_id);
 
         if(!$post)
         {
             return response([
                 'message' => 'Post not found.'
             ], 403);
         }
 
         //validate fields
         $attrs = $request->validate([
             'comment' => 'required|string'
         ]);
 
         Comment::create([
             'comment' => $attrs['comment'],
             'post_id' => $request->post_id,
             'doctor_id' => auth()->user()->id
         ]);
 
         return response([
             'message' => 'Comment created.'
         ], 200);
     }
 
     // update a comment
     public function update_1(Request $request, $id)
     {
         $comment = Comment::find($id);
 
         if(!$comment)
         {
             return response([
                 'message' => 'Comment not found.'
             ], 403);
         }
 
         if($comment->doctor_id != auth()->user()->id)
         {
             return response([
                 'message' => 'Permission denied.'
             ], 403);
         }
 
         //validate fields
         $attrs = $request->validate([
             'comment' => 'required|string'
         ]);
 
         $comment->update([
             'comment' => $attrs['comment']
         ]);
 
         return response([
             'message' => 'Comment updated.'
         ], 200);
     }
 
     // delete a comment
     public function destroy_1($id)
     {
         $comment = Comment::find($id);
 
         if(!$comment)
         {
             return response([
                 'message' => 'Comment not found.'
             ], 403);
         }
 
         if($comment->doctor_id != auth()->user()->id)
         {
             return response([
                 'message' => 'Permission denied.'
             ], 403);
         }
 
         $comment->delete();
 
         return response([
             'message' => 'Comment deleted.'
         ], 200);
     }
}
