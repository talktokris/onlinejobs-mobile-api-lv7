<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        return response()->json([
            'success'=>true,
            'data'=> State::select('id','country_id','name','code')->where('status','1')->get(),
            'message'=>'States data fetch success'
        ],200);
    }
}