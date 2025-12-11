<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\AppApi\AuthBaseController; 
use App\Models\User;
use App\Models\Profile;
use App\Models\EmployerProfile;
use App\Services\SmsGatewayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
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
            'mobile' => 'required|numeric|digits_between: 8,11', // Updated to 8-11 digits
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
        $otpExpiresAt = Carbon::now()->addMinutes(3); // OTP valid for 3 minutes

        // Get country phone code for SMS
        // Since app only supports Malaysia, use phone code 60
        // Database stores ISO code (MYS) but SMS gateway needs phone code (60)
        $countryPhoneCode = '60'; // Malaysia phone code
        
        // Send SMS if gateway is enabled
        $smsGatewayEnabled = env('SMS_GATEWAY_TWO_FACTOR', false);
        
        // Log for debugging
        Log::info('OTP Request', [
            'mobile' => $input['mobile'],
            'country_id' => $input['country_id'],
            'country_phone_code' => $countryPhoneCode,
            'sms_gateway_enabled' => $smsGatewayEnabled,
            'otp' => $genOtp
        ]);
        
        if ($smsGatewayEnabled) {
            $smsService = new SmsGatewayService();
            $formattedMobile = $smsService->formatMobileNumber($countryPhoneCode, $input['mobile']);
            
            Log::info('Sending SMS for login OTP', [
                'mobile' => $input['mobile'],
                'formatted_mobile' => $formattedMobile,
                'role' => $saveRole,
                'otp' => $genOtp
            ]);
            
            $smsResult = $smsService->sendOtp($formattedMobile, $genOtp);
            
            if (!$smsResult['success']) {
                Log::error('SMS sending failed for login OTP', [
                    'mobile' => $formattedMobile,
                    'error' => $smsResult['message'],
                    'response' => $smsResult['data'] ?? null
                ]);
                
                // Return error if SMS fails (don't save OTP if SMS gateway is enabled but failed)
                return $this->sendError('Failed to send OTP SMS.', [
                    'error' => $smsResult['message'] ?? 'Unable to send SMS. Please try again later.'
                ], 500);
            } else {
                Log::info('SMS sent successfully for login OTP', [
                    'mobile' => $formattedMobile,
                    'ref' => $smsResult['data']['ref'] ?? null
                ]);
            }
        } else {
            Log::info('SMS Gateway disabled - using default OTP for login', [
                'otp' => $genOtp
            ]);
        }

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
                ->update([
                    'otp' => $genOtp,
                    'otp_expires_at' => $otpExpiresAt
                ]);
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
            $user->otp_expires_at = $otpExpiresAt;
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
            'mobile' => 'required|numeric|digits_between: 8,11', // Updated to 8-11 digits
            'otp' => 'required|string|min:6|max:6',
            'expo_push_token' => 'nullable|string|max:255',
            'device_id' => 'nullable|string|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        // Convert OTP to string to ensure proper comparison
        $otp = (string) $request->otp;
        $mobile = (string) $request->mobile;
        $countryId = (int) $request->country_id;
        
        // Find user with matching OTP
        $user = User::where('phone', $mobile)
            ->where('country_id', $countryId)
            ->where('otp', $otp)
            ->first();
        
        if($user) {
            // Check if OTP is expired
            if ($user->otp_expires_at && Carbon::now()->gt($user->otp_expires_at)) {
                return $this->sendError('OTP expired.', ['error' => 'OTP code has expired. Please request a new one.']);
            }
            
            Auth::login($user); 
            // $user = Auth::user(); 
            
            // Update device token and device ID if provided
            if ($request->has('expo_push_token') || $request->has('device_id')) {
                $updateData = [];
                if ($request->has('expo_push_token') && !empty($request->expo_push_token)) {
                    $updateData['expo_push_token'] = $request->expo_push_token;
                }
                if ($request->has('device_id') && !empty($request->device_id)) {
                    $updateData['device_id'] = $request->device_id;
                }
                if (!empty($updateData)) {
                    $user->update($updateData);
                }
            }
            
            // Clear OTP after successful login
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            
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
        // Check if SMS gateway two-factor is enabled
        $smsGatewayEnabled = env('SMS_GATEWAY_TWO_FACTOR', false);
        
        if ($smsGatewayEnabled) {
            // Generate random 6-digit OTP (100000 to 999999)
            return str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        } else {
            // Default OTP for testing
            return '123456';
        }
    }

    /**
     * Resend OTP for login
     */
    public function clientOtpResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|numeric|min:1|digits_between: 1,999',
            'mobile' => 'required|numeric|digits_between: 8,11',
            'role' => 'required|numeric|min:1|max:2',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $saveRole = $input['role'];

        // Check if user exists
        $user = User::where('phone', $input['mobile'])
            ->where('country_id', $input['country_id'])
            ->first();

        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'Mobile number not registered']);
        }

        // Check role match
        if ($user->role_id != $saveRole) {
            $setMsg = $saveRole == 1 
                ? "This number is registered as employer account" 
                : "This number is registered as job seeker account";
            return $this->sendResponse([], $setMsg, false);
        }

        // Generate new OTP
        $genOtp = $this->optGenrate();
        $otpExpiresAt = Carbon::now()->addMinutes(3);

        // Get country phone code for SMS
        // Since app only supports Malaysia, use phone code 60
        $countryPhoneCode = '60'; // Malaysia phone code

        // Send SMS if gateway is enabled
        $smsGatewayEnabled = env('SMS_GATEWAY_TWO_FACTOR', false);
        
        Log::info('OTP Resend Request', [
            'mobile' => $input['mobile'],
            'country_id' => $input['country_id'],
            'country_phone_code' => $countryPhoneCode,
            'sms_gateway_enabled' => $smsGatewayEnabled,
            'otp' => $genOtp
        ]);
        
        if ($smsGatewayEnabled) {
            $smsService = new SmsGatewayService();
            $formattedMobile = $smsService->formatMobileNumber($countryPhoneCode, $input['mobile']);
            
            Log::info('Resending SMS for login OTP', [
                'mobile' => $input['mobile'],
                'formatted_mobile' => $formattedMobile,
                'role' => $saveRole,
                'otp' => $genOtp
            ]);
            
            $smsResult = $smsService->sendOtp($formattedMobile, $genOtp);
            
            if (!$smsResult['success']) {
                Log::error('SMS resend failed for login OTP', [
                    'mobile' => $formattedMobile,
                    'error' => $smsResult['message'],
                    'response' => $smsResult['data'] ?? null
                ]);
                
                // Return error if SMS fails (don't save OTP if SMS gateway is enabled but failed)
                return $this->sendError('Failed to resend OTP SMS.', [
                    'error' => $smsResult['message'] ?? 'Unable to send SMS. Please try again later.'
                ], 500);
            } else {
                Log::info('SMS resent successfully for login OTP', [
                    'mobile' => $formattedMobile,
                    'ref' => $smsResult['data']['ref'] ?? null
                ]);
            }
        } else {
            Log::info('SMS Gateway disabled - using default OTP for resend', [
                'otp' => $genOtp
            ]);
        }

        // Update OTP in database
        $user->otp = $genOtp;
        $user->otp_expires_at = $otpExpiresAt;
        $user->save();

        $message = $smsGatewayEnabled 
            ? 'OTP resent successfully.'
            : 'OTP regenerated successfully. Please use the default OTP code.';

        return $this->sendResponse([], $message);
    }

    public function getMarginPer(){

        return 10 ;
        
    }

    
}