<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ChildParent;
use App\Models\Doctor;

use App\Models\Post;
use Illuminate\Support\Facades\Config;




class PostController extends Controller

{
 
    public function __construct()
{
    $this->middleware('auth:doctor-api,ChildParent-api');
}





    // get all posts
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('child_parent:id,name,image')->with('doctor:id,name,image')->withCount('comments', 'likes')
            ->with('likes', function($like){
                return $like->where('child_parent_id', auth()->user()->id)
                    ->select('id', 'child_parent_id', 'post_id')->get();
            })
            ->get()
        ], 200);
    }

    // get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    // create a post
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'child_parent_id' => auth()->user()->id,
            'image' => $image
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post created.',
            'post' => $post,
        ], 200);
    }
    
    // update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->child_parent_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string',
           
        ]);

        //$image = time()."-".$request->file("posts")->getClientOriginalName()."-".$request->file("posts")->extension();
       $image = $this->saveImage($request->image,'posts');
        //$attrs = $request->validate([
         //   'body' => 'required|string',
           // 'image'=>''
        //]);

        $post->update([
            'body' =>  $attrs['body'],
            'image'=>$image

        ]);

        // for now skip for post image

        return response([
            'message' => 'Post updated.',
            'post' => $post
        ], 200);
    }
   
    //delete post
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->child_parent_id != auth()->user()->id )
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.'
        ], 200);
    }
   


////////////doctoorrr/////////



    public function index_1()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('doctor:id,name,image')->with('child_parent:id,name,image')->withCount('comments', 'likes')
            ->with('likes', function($like){
                return $like->where('doctor_id', auth()->user()->id)
                    ->select('id', 'doctor_id', 'post_id')->get();
            })
            ->get()
        ], 200);
    }

    public function show_1($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    public function store_1(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'doctor_id' => auth()->user()->id,

            'image' => $image
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post created.',
            'post' => $post,
        ], 200);
    }

    public function update_1(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->doctor_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);
      // $image = time()."-".$request->file("image")->getClientOriginalName()."-".$request->file("image")->extension();

        $image = $this->saveImage($request->image,'posts');

        $post->update([
            'body' => $attrs['body'],
            'image'=>$image
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post updated.',
            'post' => $post
        ], 200);
    }
    public function destroy_1($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->doctor_id != auth()->user()->id )
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.'
        ], 200);
    }



}
