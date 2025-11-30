<?php

namespace App\Http\Controllers\Api;


use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        // return Country::where('id',1)->get();

        return response()->json([
            'success'=>true,
            'data'=> Country::select('id','name','code')->where('status',1)->get(),
            'message'=>'Country data fetch success'
        ],200);
    }
}