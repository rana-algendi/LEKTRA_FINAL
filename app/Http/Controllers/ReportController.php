<?php

namespace App\Http\Controllers;
use App\Models\ChildParent;
use App\Models\Doctor;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;



class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth:doctor-api,ChildParent-api');
    }
    

    
    
    
        //get all reports doctor
        public function index()
        {       
            return response([
                'reports' => Report::orderBy('created_at', 'desc')->where('doctor_id', auth()->user()->id)->get()
                //->select('id', 'doctor_id', 'report_id')->get();
            ], 200);
        }
        public function index_1()
        {       
            return response([
                'reports' => Report::orderBy('created_at', 'desc')->where('child_parent_id', auth()->user()->id)->get()
                //->select('id', 'doctor_id', 'report_id')->get();
            ], 200);
        }
       
    
       
    

       
        //بيعرض الريبورت الي لسه مكرياتاه مش كل الريبورتس بتاعت الدكتور
       // public function index() {
         //   return response()->json(auth('doctor-api'));
        //}

      
    
        // get single report doctor
        public function show(Request $request, $id)
        {
            $report = Report::find($id);
    
            if(!$report)
            {
                
                return response([
                    'message' => 'Report not found.'
                ], 403);
            }
    
            if($report->doctor_id != auth()->user()->id)
            {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }
            return response([
                'report' => Report::where('id', $id)->get()
            ], 200);
        }



         // get single report parent
         public function show_1(Request $request, $id)
        {
            $report = Report::find($id);
    
            if(!$report)
            {
                return response([
                    'message' => 'Report not found.'
                ], 403);
            }
    
            if($report->child_parent_id != auth()->user()->id)
            {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }
            return response([
                'report' => Report::where('id', $id)->get()
            ], 200);
        }
    
        // create a report
        public function store(Request $request)
        {
            //validate fields
            $attrs = $request->validate([
                'child_name' => 'required|string',
                'father_name'=> 'required|string',
                'mother_name'=> 'required|string',
                'national_id'=> 'required|string',
                'blood_type'=> 'required|string',
                'age'=> 'required|string',
                'birthday'=> 'required',
                'weight'=> 'required|string',
                'height'=> 'required|string',
                'started_in'=> 'required',
                'last_time'=> 'required',
                'diagnosis'=> 'required|string',
            ]);

            $image = $this->saveImage($request->child_image,'reports');
            $report = Report::create([
                'child_parent_id' => $request->child_parent_id,
                //'image_id' => $request->image_id,
                'doctor_id' => auth()->user()->id,
                'child_image' => $image,
                'child_name' => $attrs['child_name'],
                'father_name'=> $attrs['father_name'],
                'mother_name'=> $attrs['mother_name'],
                'national_id'=> $attrs['national_id'],
                'blood_type'=> $attrs['blood_type'],
                'age'=> $attrs['age'],
                'birthday'=> $attrs['birthday'],
                'weight'=> $attrs['weight'],
                'height'=>$attrs['height'],
                'started_in'=> $attrs['started_in'],
                'last_time'=> $attrs['last_time'],
                'diagnosis'=> $attrs['diagnosis'],
            ]);
    
            // for now skip for post image
    
            return response([
                'message' => 'Report created.',
                'report' => $report,
            ], 200);
        }
        
        // update a report
        public function update(Request $request, $id)
        {
            $report = Report::find($id);
    
            if(!$report)
            {
                return response([
                    'message' => 'Report not found.'
                ], 403);
            }
    
            if($report->doctor_id != auth()->user()->id)
            {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }
    
            //validate fields
            $attrs = $request->validate([
                'child_name' => 'string',
                'father_name'=> 'string',
                'mother_name'=> 'string',
                'national_id'=> 'string',
                'blood_type'=> 'string',
                'age'=> 'string',
                'birthday'=> 'string',
                'weight'=> 'string',
                'height'=> 'string',
                'started_in'=> 'string',
                'last_time'=> 'string',
                'diagnosis'=> 'string',          
              ]);

            $image = $this->saveImage($request->image, 'reports');
            $report->update([
                'child_image'=>$image,
                'child_name' =>$request->child_name,
                'father_name'=> $request->father_name,
                'mother_name'=> $request->mother_name,
                'national_id'=>$request->national_id,
                'blood_type'=>$request->blood_type,
                'age'=>$request->age,
                'birthday'=>$request->birthday,
                'weight'=>$request->weight,
                'height'=>$request->height,
                'started_in'=>$request->started_in,
                'last_time'=> $request->last_time,
                'diagnosis'=> $request->diagnosis,         ]);
    
    
            return response([
                'message' => 'Report updated.',
                'report' => $report
            ], 200);

        }
       
        //delete report
        public function destroy($id)
        {
            $report = Report::find($id);
    
            if(!$report)
            {
                return response([
                    'message' => 'report not found.'
                ], 403);
            }
    
            if($report->doctor_id != auth()->user()->id )
            {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }
    
            $report->delete();
    
            return response([
                'message' => 'report deleted.'
            ], 200);
        }
       
    
    
    
}
