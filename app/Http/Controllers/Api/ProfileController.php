<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Http\Controllers\Api\StatusFillsController;


use App\Http\Resources\UserProfileResource;
use App\Http\Resources\CountryResource;

class ProfileController extends Controller
{
    
    public function profile(Request $request){

        $id = auth('sanctum')->user()->id;
  
        $user_info = User::where('id',$id)->get();
  
  
      // return $user_info;
  
       //return UserProfileResource::collection($user_info);
  
  
       //$country= Country::get();
       //$user_info= User::get();
  
  
       $statusObj = new StatusFillsController;
       $stausMsg =$statusObj->status();
  
  
  
       
          return response()->json([
              'status' => 'success',
              'results' => UserProfileResource::collection($user_info),
              'options' => $stausMsg,
            
          ]);
       }
}