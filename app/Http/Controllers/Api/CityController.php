<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        return response()->json([
            'success'=>true,
            'data'=> City::select('id','state_id','name','code')->where('status','1')->get(),
            'message'=>'City data fetch success'
        ],200);
    }
}