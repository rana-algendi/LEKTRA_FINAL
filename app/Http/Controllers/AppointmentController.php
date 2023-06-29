<?php

namespace App\Http\Controllers;
use App\Models\ChildParent;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AppointmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:doctor-api,ChildParent-api');
    }
    
    public function index()
    {
        // get all Appointments
        return response([
        'appointments' => Appointment::orderBy('created_at', 'desc')->where('child_parent_id', auth()->user()->id)->with('doctor:id,name,image')
        ->get()
    ], 200);
    }

     // get single appointment 
     public function show(Request $request, $id)
     {
         $appointment = Appointment::find($id);
 
         if(!$appointment)
         {
             return response([
                 'message' => 'appointment not found.'
             ], 403);
         }
 
         if($appointment->child_parent_id != auth()->user()->id)
         {
             return response([
                 'message' => 'Permission denied.'
             ], 403);
         }
         return response([
             'appointment' => Appointment::where('id', $id)->get()
         ], 200);
     }


      // create an Appointment
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'date' => 'required|string',
            'day' => 'required|string',
            'time' => 'required|string',
            'status' => 'string'



        ]);


        $appointment = Appointment::create([


            'child_parent_id' => auth()->user()->id,
            'doctor_id' => $request->doctor_id,
            'date' => $attrs['date'],
            'day'=> $attrs['day'],
            'time'=> $attrs['time'],
            'status'=> $attrs['status'],
           
        ]);


        return response([
            'message' => 'appointment created.',
            'appointment' => $appointment,
        ], 200);
    }



    // update an appointment
    public function update(Request $request, $id)
    {

        $appointment = Appointment::find($id);

        if(!$appointment)
        {
            return response([
                'message' => 'appointment not found.'
            ], 403);
        }

        if($appointment->child_parent_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'date' => 'string',
            'day'=> 'string',
            'time'=> 'string',
           // 'status'=> 'string',
                   ]);

        $appointment->update([
            'date' => $attrs['date'],
            'day'=> $attrs['day'],
            'time'=> $attrs['time'],
           // 'status'=> $attrs['status'],
                       ]);


        return response([
            'message' => 'appointment updated.',
            'appointment' => $appointment
        ], 200);

    }

    //delete appointment
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if(!$appointment)
        {
            return response([
                'message' => 'appointment not found.'
            ], 403);
        }

        if($appointment->child_parent_id != auth()->user()->id )
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $appointment->delete();

        return response([
            'message' => 'appointment deleted.'
        ], 200);
    }

    /////////doctor/////////
    public function index_1()
    {
        // get all Appointments
        return response([
        'appointments' => Appointment::orderBy('created_at', 'desc')->where('child_parent_id', auth()->user()->id)->with('child_parent:id,name,image')->get()
    ], 200);
    }

     // get single appointment doctor
     public function show_1(Request $request, $id)
     {
         $appointment = Appointment::find($id);
 
         if(!$appointment)
         {
             return response([
                 'message' => 'appointment not found.'
             ], 403);
         }
 
         if($appointment->doctor_id != auth()->user()->id)
         {
             return response([
                 'message' => 'Permission denied.'
             ], 403);
         }
         return response([
             'appointment' => Appointment::where('id', $id)->get()
         ], 200);
     }
}
