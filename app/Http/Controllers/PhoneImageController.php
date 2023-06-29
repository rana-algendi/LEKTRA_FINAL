<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhoneImageController extends Controller
{

    public function uploadimage(PhoneImageRequest $request){

        $photo = fopen($request->file("file"),'rb');
        $response = Http::attach('file',$photo)->post("http://127.0.0.1:5000/true");
        fclose($photo);
        return $response;
    }
}
