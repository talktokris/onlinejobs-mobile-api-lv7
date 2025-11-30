<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ProfessionalProfile;
use App\Models\EmployerProfile;
use App\Models\AgentProfile;
use Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
    }
    public function seekerSignUp(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            return response()->json([
                'success' => false,
                'error' => 'User already exists'
            ], 403);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->otp = rand(1000, 9999);
            $user->password = Hash::make($request->password);
            $user->public_id = time() . md5($request->email);
            $user->save();
            $user->attachRole('professional');
            $professional = new ProfessionalProfile;
            $professional->user_id = $user->id;
            $professional->name = $request->name;
            $professional->first_name = $request->name;
            $professional->email = $request->email;
            $professional->phone = $request->phone;
            $professional->save();
            SendEmail($request->email, 'Activate Your Email', 'Your OTP for email activation is <b>' . $user->otp . '</b> ');
            return response()->json([
                'success' => true,
                'message' => 'Job Seeker registered successfully'
            ], 200);
        }
    }
    public function employerSignUp(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'company_phone' => 'required',
            'password' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            return response()->json([
                'success' => false,
                'error' => 'User already exists'
            ], 403);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->company_phone;
            $user->otp = rand(1000, 9999);
            $user->password = Hash::make($request->password);
            $user->public_id = time() . md5($request->email);
            $user->save();
            $user->attachRole('employer');
            $employer = new EmployerProfile;
            $employer->user_id = $user->id;
            $employer->company_name = $request->company_name;
            $employer->name = $request->name;
            $employer->company_email = $request->email;
            $employer->company_phone = $request->company_phone;
            $employer->save();
            SendEmail($request->email, 'Activate Your Email', 'Your OTP for email activation is <b>' . $user->otp . '</b> ');
            return response()->json([
                'success' => true,
                'message' => 'Employer Registered Successfully'
            ], 200);
        }
    }
    public function partnerSignUp(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'agency_registered_name' => 'required',
            'name' => 'required',
            'agency_email' => 'required|email',
            'agency_phone' => 'required',
            'password' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            return response()->json([
                'success' => false,
                'error' => 'User already exists'
            ], 403);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->agency_email;
            $user->phone = $request->agency_phone;
            $user->otp = rand(1000, 9999);
            $user->password = Hash::make($request->password);
            $user->public_id = time() . md5($request->agency_email);
            $user->save();
            $user->attachRole('agent');
            $agent = new AgentProfile;
            $agent->user_id = $user->id;
            $agent->agent_code = time();
            $agent->agency_registered_name = $request->agency_registered_name;
            $agent->first_name = $request->name;
            $agent->agency_email = $request->agency_email;
            $agent->agency_phone = $request->agency_phone;
            $agent->save();
            SendEmail($request->agency_email, 'Activate Your Email', 'Your OTP for email activation is <b>' . $user->otp . '</b> ');
            return response()->json([
                'success' => true,
                'message' => 'Agent Registered Successfully'
            ], 200);
        }
    }
}