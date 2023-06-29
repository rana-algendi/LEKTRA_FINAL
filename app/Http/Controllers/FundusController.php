<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FundusController extends Controller
{

    public function uploadFundus(FundusRequest $request){

        $photo = fopen($request->file("file"),'rb');
        $response = Http::attach('file',$photo)->post("http://127.0.0.1:5000/success");
        fclose($photo);
        return $response;
    }
}
