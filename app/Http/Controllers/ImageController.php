<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Report;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor-api,ChildParent-api');
    }



     // get all images of a report
    public function index($id)
    {
        
        $report = Report::find($id);

        if(!$report)
        {
            return response([
                'message' => 'report not found.'
            ], 403);
        }

        return response([
            'images' => $report->images()->where('doctor_id', auth()->user()->id)
            ->get()
        ], 200);
    }

//get single image
    public function show(Request $request, $id)
    {
        $image = Image::find($id);

        if(!$image)
        {
            return response([
                'message' => 'image not found.'
            ], 403);
        }

        if($image->doctor_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }
        return response([
            'image' => Image::where('id', $id)->get()
        ], 200);
    }
      
  
      
    // create a image
    public function store(Request $request)
    {
        $report = Report::find($request->report_id);

        if(!$report)
        {
            return response([
                'message' => 'image not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'image' => '',
            'descrpition' => 'string',
            'title'=>'string',
            'taken_at'=>''

        ]);
        $image = $this->saveImage($request->image, 'images');

        Image::create([
            'image' => $image,
            'report_id' => $request->report_id,
           // 'child_parent_id' => $request->child_parent_id,
            'doctor_id'=> auth()->user()->id,
            'title' => $attrs['title'],
            'descrpition' => $attrs['descrpition'],
            'taken_at' => $attrs['taken_at']


        ]);

        return response([
            'message' => 'image created.'
        ], 200);
    }




    // update a image
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if(!$image)
        {
            return response([
                'message' => 'image not found.'
            ], 403);
        }

        if($image->doctor_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
           // 'image' => 'required|string'
            'image' => '',
            'descrpition' => 'string',
            'title'=>'string',
            'taken_at'=>''
        ]);
        $images = $this->saveImage($request->image,'images');

        $image->update([
            'image' => $images,
            //'report_id' => $request->report_id,
            //'child_parent_id' => auth()->user()->id,
            //'doctor_id'=> auth()->user()->id,
            'title' => $attrs['title'],
            'descrpition' => $attrs['descrpition'],
            'taken_at' => $attrs['taken_at']
        ]);

        return response([
            'message' => 'image updated.'
        ], 200);
    }

    // delete a comment
    public function destroy($id)
    {
        $image = Image::find($id);

        if(!$image)
        {
            return response([
                'message' => 'image not found.'
            ], 403);
        }

        if($image->doctor_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $image->delete();

        return response([
            'message' => 'image deleted.'
        ], 200);
    }

}
