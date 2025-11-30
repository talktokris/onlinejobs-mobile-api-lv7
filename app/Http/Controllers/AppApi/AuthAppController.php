<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\AppApi\AuthBaseController; 
use App\Models\User;
use App\Models\Profile;
use App\Models\EmployerProfile;
use Illuminate\Support\Facades\Auth;
use Validator;


class AuthAppController extends AuthBaseController
{
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

        // return $success;
   
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

    public function clientOtpRequest(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|numeric|min:1|digits_between: 1,999',
            'mobile' => 'required|numeric|min:1|digits_between: 1,99999999999',
            'role' => 'required|numeric|min:1|max:2',
            
        ]);

        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

       
        
        $input = $request->all();

        $saveRole=$input['role'];
        // return $saveRole;
        $findNumber =User::where([['phone','=', $input['mobile']],['country_id','=', $input['country_id']]])->count(); 

        $findNumberStatus =User::where([['phone','=', $input['mobile']],['country_id','=', $input['country_id']],['role_id','!=',$saveRole]])->count(); 

        
        // return $findNumber;

        $genOtp = $this->optGenrate(); // genrate new otp

        if($findNumber>=1)
        {
            // Setting Message for roles
            if($saveRole==1){ $setMsg ="This number is registered as employer account";}
            elseif($saveRole==2){ $setMsg ="This number is registered as job seeker account";}
            else {$setMsg ="This number is associated as different Account";}

            if($findNumberStatus>=1){
                return $this->sendResponse([], $setMsg, false);
            }else {
                $user = User::where('country_id', $input['country_id'])
                ->where('phone', $input['mobile'])
                ->update(['otp' => $genOtp]);
                return $this->sendResponse($user, 'Otp requested successfully.');
            }


        } 
        else 
        {
            $user = new User;
            $user->name = '';
            $user->email = '';
            $user->password = '';
            $user->country_id = $request->country_id;
            $user->phone = $request->mobile;
            $user->public_id =  time() . md5($request->mobile);
            $user->role_id = $saveRole;
            $user->otp = $genOtp;
            $user->save();

            if($user->role_id==1){
                $saveItem = new Profile;
                $saveItem->user_id = $user->id;
                $saveItem->country = $request->country_id;
                $saveItem->save();
                if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $saveItem->id; $message='User Profile created successfully'; }
            }

            elseif($user->role_id==2){
                $saveItem = new EmployerProfile;
                $saveItem->user_id = $user->id;
                $saveItem->country = $request->country_id;
                $saveItem->company_country = $request->country_id;
                $saveItem->save();
                if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $saveItem->id; $message='Employer profile created successfully'; }
            }
   
   

            return $this->sendResponse($user, 'Account created and otp requested successfully.');
        }

    
    }

    public function clientOtpLogin(Request $request)
    {

        // $statusObj = new StatusFillsController;
        // $stausMsg =$statusObj->status();
        
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|numeric|min:1|digits_between: 1,999',
            'mobile' => 'required|numeric|min:1|digits_between: 1,99999999999',
            'otp' => 'required|string|min:6|max:6',
            
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
            $findNumber =User::where([['phone','=', $request->mobile],['country_id','=',  $request->country_id],['otp','=',  $request->otp]])->count(); 
        // return $findNumber;

        if($findNumber>=1){ 
            $user = User::where([['phone','=', $request->mobile],['country_id','=',  $request->country_id],['otp','=',  $request->otp]])->first();  
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