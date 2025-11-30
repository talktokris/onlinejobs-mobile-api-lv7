<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController; 
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Api\StatusFillsController;

class RegisterController extends BaseController
{
     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

     
     public function clientRegisterEmail(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required',
             'email' => 'required|email',
             'password' => 'required',
             'c_password' => 'required|same:password',
         ]);
    
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors());       
         }
    
         $input = $request->all();
         $input['password'] = bcrypt($input['password']);
         $input['role_id'] = 2;
         $user = User::create($input);
         $success['token'] =  $user->createToken('HomeFoodMobileApp')->plainTextToken;
         $success['name'] =  $user->name;
    
         return $this->sendResponse($success, 'User register successfully.');
     }
    
     /**
      * Login api
      *
      * @return \Illuminate\Http\Response
      */
     public function clientLoginEmail(Request $request)
     {
 
  
         if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=>2])){ 
             $user = Auth::user(); 
             $success['token'] =  $user->createToken('HomeFoodMobileApp')->plainTextToken; 
             $success['name'] =  $user->name;
    
             return $this->sendResponse($success, 'User login successfully.');
         } 
         else{ 
             return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
         } 
         
     }
 
   
     


 /*========== Client Otp Login & Register =============*/
 
 public function clientOtpRequest(Request $request) {
    $validator = Validator::make($request->all(), [
        'country_id' => 'required|numeric|min:1|digits_between: 1,999',
        'phone' => 'required|numeric|min:1|digits_between: 1,99999999999',
      
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }


    $input = $request->all();
    $findNumber =User::where([['phone','=', $input['phone']],['country_id','=', $input['country_id']]])->count(); 

    $findNumberStatus =User::where([['phone','=', $input['phone']],['country_id','=', $input['country_id']],['role_id','!=',1]])->count(); 

   // return $findNumber;

   $genOtp = $this->optGenrate(); // genrate new otp

   if($findNumber>=1){

        if($findNumberStatus>=1){
            return $this->sendResponse([], 'This number in use as service provider account.', false);
        }else {
            $user = User::where('country_id', $input['country_id'])
            ->where('phone', $input['phone'])
            ->update(['otp' => $genOtp]);
            return $this->sendResponse($user, 'Otp requested successfully.');
        }


   } 
   else 
   {
    $input['role_id'] = 1;
    // $input['app_margin_per'] = 0;
    $input['otp'] = $genOtp;
    $user = User::create($input);
    return $this->sendResponse($user, 'Account created and otp requested successfully.');
   }

   
}

public function clientOtpLogin(Request $request)
{

    $statusObj = new StatusFillsController;
    $stausMsg =$statusObj->status();
    
    $validator = Validator::make($request->all(), [
        'country_id' => 'required|numeric|min:1|digits_between: 1,999',
        'phone' => 'required|numeric|min:1|digits_between: 1,99999999999',
        'otp' => 'required|string|min:6|max:6',
      
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }
     $findNumber =User::where([['phone','=', $request->phone],['country_id','=',  $request->country_id],['role_id','=', 1],['otp','=',  $request->otp]])->count(); 
    // return $findNumber;

    if($findNumber>=1){ 
        $user = User::where([['phone','=', $request->phone],['country_id','=',  $request->country_id],['role_id','=', 1],['otp','=',  $request->otp]])->first();  
        Auth::login($user); 
       // $user = Auth::user(); 
        $success['token'] =  $user->createToken('OnlineJobsToken')->plainTextToken; 
        $success['name'] =  $user->name;
        // $success['options'] = $stausMsg;

        return $this->sendResponse($success, 'User login successfully.');
    } 
    else{ 
        return $this->sendError('Invalid OTP code.', ['error'=>'Incorrect otp code']);
    } 

   
}

 
        public function optGenrate(){
 
         return 123456 ;
         
        }
 
        public function getMarginPer(){
 
         return 10 ;
         
        }
   
}