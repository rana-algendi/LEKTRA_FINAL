<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChildParent;
use App\Models\Doctor;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Hash;

class ChildParentController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard','ChildParent-api');
    }






    public function login(Request $request)
    {
      $validator= Validator::make($request->all(),[
        'email' => 'required|email' ,
        'password' => 'required|string|min:6',
      ]);
      if ($validator->fails()) {
        return response()->json($validator->errors(),422); 
      }
      if (! $token = auth() -> attempt($validator -> validated())) {
        return response()->json(['error' => 'Unauthorized'],); 
      }
      return $this->createNewToken($token);
    }






   
 
    public function register(Request $request)
    {

      $validator= Validator::make($request->all(),[
        'name' => 'required|string|between:2,100' ,
        'email' => 'required|string|email|max:100|unique:users',
        'password' => 'required|string|confirmed|min:6',
        'phone' => 'string|min:11' ,
        'image' =>'' 


      ]);
      if ($validator->fails()) {
        return response()->json($validator->errors()->toJson(),400); 
      }
      $image = $this->saveImage($request->image, 'profiles');

      $user = ChildParent::create(array_merge(
        
        $validator->validated(),
        ['password'=>bcrypt($request->password),
        'image' => $image,
        ]
      ));
      return response()->json([
        'message' => 'Parent successfully registerd' ,
        'user' => $user
      ],201);
    }


    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Parent successfully signed out ']);
    }



    public function userProfile() {
        return response()->json(auth()->user());
    }

     
 
 



    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token ,
            'token_type' => 'bearer' ,
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
            'user' => auth()->user()

        ]);
    }
    
    
    

    // update user
    public function update(Request $request)
    {


      //return response()->json(['request' => $request->file('image')->extension()]);
       // $attrs = $request->validate([
         //   'name' => 'string',
        //]);

     //  $image = time()."-".$request->file("image")->getClientOriginalName()."-".$request->file("image")->extension();
        $image = $this->saveImage($request->image, 'profiles');
        auth()->user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'image' => $image,
            //'password' => Hash::make($request->password),
        ]);

        return response([
            'message' => 'parent updated.',
            'user' => auth()->user()
        ], 200);
    }






     // get all doctors
     public function index()
     {
         return response([
             'doctors' => Doctor::orderBy('created_at', 'desc')//->with('doctor:id,name,image')->with('doctor:id,name,image')
             
             
             ->get()
         ], 200);
     }




}
