<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\hospital;
use App\Models\ChildParent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Hash;

class HospitalController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard','ChildParent-api');
    }   
    


    public function index()
    {
        return response([
            'hospitals' => Hospital::orderBy('created_at', 'desc')//->with('child_parent:id,name,image') 
            ->get()
        ], 200);
    } 

    // get single hospital
    public function show($id)
    {
        return response([
            'hospital' => Hospital::where('id', $id)->get()
        ], 200);
    }



   // public function upload(Request $request)
   // {
    //  $validator= Validator::make($request->all(),[
     //   'name' => 'required|string|between:2,100' ,
      //  'about' => 'string|between:2,100',
       // 'address' => 'string|between:2,100',
       // 'phone' => 'string|min:11' ,
       // 'image' => '' ,


     // ]);
     // if ($validator->fails()) {
      //  return response()->json($validator->errors()->toJson(),400); 
     // }
      //$image = $this->saveImage($request->image, 'profiles');

      //$user = Hospital::create(array_merge(
        //$validator->validated(),
        //['password'=>bcrypt($request->password),
        //'image' => $image ,
        
        //]
      //));
      //return response()->json([
        //'message' => 'Hospital successfully Added' ,
        //'user' => $user
      //],201);
    //}
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
          'name' => 'required|string|between:2,100' ,
          'about' => 'string|between:2,100',
          'address' => 'string|between:2,100',
          'phone' => 'string|min:11' ,
          'image' => '' 
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        $hospital = Hospital::create([
            'name' => $attrs['name'],
            'about' => $attrs['about'],
            'address' =>$attrs['address'],
            'phone' => $attrs['phone'],
            'child_parent_id' => auth()->user()->id,
            'image' => $image
        ]);

       

        return response([
            'message' => 'Hospital created.',
            'Hospital' => $hospital,
        ], 200);
    }
    
   
    
}
