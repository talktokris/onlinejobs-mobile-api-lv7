<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\EmployerProfile;
use App\Models\AgentProfile;
use App\Models\ProfessionalProfile;
use App\Models\Qualification;
use App\Models\ProfessionalExperience;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;

class UserController extends Controller
{
    // use AuthenticatesUsers;
    use RegistersUsers;

    function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is invalid'
            ], 401);
        }


        if ($user->status == 0 && $user->is_email_verified == True) {
            return response()->json([
                'success' => false,
                'message' => 'Your application is pending. Please contact support team.'
            ], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;
        $role = RoleUser::select('role_id')->whereIn('role_id', [3, 4, 7])->where('user_id', $user->id)->first();
        // $allowedRoles=array(3,4,7);
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Unknown Error. Please contact support team.'
            ], 401);
        }
        $response = [
            'user' => $user,
            'token' => $token,
            'role' => $role->role_id,
        ];
        return response()->json([
            'success' => true,
            'data' => $response,
            'message' => 'Login Success'
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }

    public function getProfile(Request $request)
    {
        $user_id = $request->user()->id;
        $user = User::where('id', $user_id)->first();
        $role = RoleUser::select('role_id')->whereIn('role_id', [3, 4, 7])->where('user_id', $user_id)->first();
        // return $role;
        if ($role->role_id == 3) {
            $profile = EmployerProfile::where('user_id', $user_id)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'employer' => $profile
                ],
                'message' => 'Profile Data'
            ], 200);
        } elseif ($role->role_id == 4) {
            $profile = AgentProfile::where('user_id', $user_id)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'agent' => $profile
                ],

                'message' => 'Profile Data'
            ], 200);
        } else {
            $profile = ProfessionalProfile::where('user_id', $user_id)->first();
            $qualification = Qualification::where('user_id', $user_id)->get();
            $experience = ProfessionalExperience::where('user_id', $user_id)->get();
            $profile['qualifications'] = $qualification;
            $profile['experiences'] = $experience;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'professional' => $profile,
                ],
                'message' => 'Profile Data'
            ], 200);
        }
    }


    // email verify

    public function verifyEmail(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'error' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            $user = User::where('email', '=', $request->email)->first();
            if ($user->otp == $request->otp) {
                $user->is_email_verified = True;
                $user->status = 1;
                $user->save();
                SendEmail($request->email, 'Congratulations', 'Your Email is Verified');
                return response()->json([
                    'success' => true,
                    'message' => 'Job Seeker registered successfully'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Please Check Your Email And OTP'
                ], 401);
            }
        };
    }

    // Request OTP
    public function requestOtp(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'error' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            $user = User::where('email', '=', $request->email)->first();
            $user->otp = rand(1000, 9999);
            $user->save();
            SendEmail($request->email, 'Here is your OTP', 'Your OTP to Chnage Password is <b>' . $user->otp . '</b> ');
            return response()->json([
                'success' => true,
                'message' => 'you have got your OTP on your email'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'You do not have account with us',
            ], 401);
        };
    }
    // Change Password
    public function changePassword(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'error' => $valid->errors()
            ], 401);
        } else if (User::where('email', '=', $request->email)->count() > 0) {
            $user = User::where('email', '=', $request->email)->first();
            if ($user->otp == $request->otp) {
                $user->password = Hash::make($request->password);
                $user->save();
                SendEmail($request->email, 'Password Changed', 'You have successfully changed your password');
                return response()->json([
                    'success' => true,
                    'message' => 'You have successfully changed your password'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Error Occurred',
                ], 401);
            }
        }
    }



    public function updatePartnerProfile(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'agency_registered_name' => 'required',
            'agency_registration_no' => 'required',
            'license_no' => 'required',
            'agency_country' => 'required',
            'agency_state' => 'required',
            'agency_city' => 'required',
            'first_name' => 'required',
            'contact_phone' => 'required',
            'contact_phone2' => 'required',

        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 422);
        } else {
            $user_id = $request->user()->id;
            $profile = AgentProfile::where('user_id', $user_id)->first();

            // update profile
            $profile->agent_code = $request->agent_code;
            $profile->agency_registered_name = $request->agency_registered_name;
            $profile->agency_address = $request->agency_address;
            $profile->agency_city = $request->agency_city;
            $profile->agency_country = $request->agency_country;
            $profile->agency_phone = $request->agency_phone;
            $profile->agency_fax = $request->agency_fax;
            $profile->license_no = $request->license_no;
            $profile->license_issue_date = $request->license_issue_date;
            $profile->license_expire_date = $request->license_expire_date;
            $profile->license_file = $request->license_file;
            $profile->first_name = $request->first_name;
            $profile->middle_name = $request->middle_name;
            $profile->last_name = $request->last_name;
            $profile->designation = $request->designation;
            $profile->address = $request->address;
            $profile->nationality = $request->nationality;
            $profile->passport = $request->passport;
            $profile->passport_file = $request->passport_file;
            $profile->nic = $request->nic;
            $profile->contact_phone = $request->contact_phone;
            $profile->contact_email = $request->contact_email;
            $profile->agency_registration_no = $request->agency_registration_no;
            $profile->agent_type = $request->agent_type;
            $profile->agency_state = $request->agency_state;
            $profile->contact_phone2 = $request->contact_phone2;
            $profile->save();
            return response()->json([
                'success' => true,
                'message' => 'Partner Profile Updated'
            ], 200);
        };
    }

    public function updateSeekerProfile(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'nric' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'expected_salary' => 'required',
            'job_category' => 'required',
            'skills' => 'required',
            'it_skills' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 422);
        } else {
            $user_id = $request->user()->id;
            $profile = ProfessionalProfile::where('user_id', $user_id)->first();
            $qualifications = Qualification::where('user_id', $user_id)->get();
            $experiences = ProfessionalExperience::where('user_id', $user_id)->get();

            // update profile
            
            $profile->first_name = $request->first_name;
            $profile->last_name = $request->last_name;
            $profile->name = $request->first_name . ' ' . $request->last_name;
            $profile->phone  = $request->phone;
            $profile->address  = $request->address;
            $profile->nric  = $request->nric;
            $profile->dob  = $request->dob;
            $profile->country  = $request->country;
            $profile->state  = $request->state;
            $profile->city  = $request->city;
            $profile->expected_salary  = $request->expected_salary;
            $profile->job_category  = $request->job_category;
            $profile->skills  = $request->skills;
            $profile->it_skills  = $request->it_skills;
            Qualification::where('user_id', $user_id)->delete();
            ProfessionalExperience::where('user_id', $user_id)->delete();
            $profile->save();
            foreach ($request->experiences as $e) {
                $ex = $e;
                $ex['user_id'] = $user_id;
                $experience = ProfessionalExperience::create($ex->all());
                $experience->save();
            }
            foreach ($request->academic_qualifications as $a) {
                $ac = $a;
                $ac['user_id'] = $user_id;
                $qualification = Qualification::create($ac->all());
                $qualification->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Seeker Profile Updated'
            ], 200);
        };
    }



    public function updateEmployerProfile(Request $request)
    {
        $valid = Validator::make($request->all(), [
            // 'company_phone' => 'required',
            'company_country' => 'required',
            // 'company_state' => 'required',
            'company_city' => 'required',
            // 'contact_name' => 'required',

        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 422);
        } else {
            $user_id = $request->user()->id;
            $profile = EmployerProfile::where('user_id', $user_id)->first();

            // update profile
            $profile->address = $request->address;
            $profile->country = $request->country;
            $profile->company_name = $request->company_name;
            $profile->company_address = $request->company_address;
            $profile->company_city = $request->company_city;
            $profile->company_country = $request->company_country;
            $profile->nric = $request->nric;
            $profile->roc = $request->roc;
            $profile->state = $request->state;
            $profile->name = $request->name;
            $profile->phone = $request->phone;
            $profile->company_email = $request->company_email;
            $profile->company_phone = $request->company_phone;
            $profile->contact_email = $request->contact_email;
            $profile->website = $request->website;
            $profile->looking_for_pro = $request->looking_for_pro;
            $profile->looking_for_gw = $request->looking_for_gw;
            $profile->looking_for_dm = $request->looking_for_dm;
            $profile->company_logo = $request->company_logo;
            $profile->postcode = $request->postcode;
            $profile->looking_for_rp = $request->looking_for_rp;
            $profile->agent_code = $request->agent_code;
            $profile->employer_type = $request->employer_type;

            $profile->save();
            return response()->json([
                'success' => true,
                'message' => 'Employer Profile Updated'
            ], 200);
        };
    }



    public function getProfileApp(Request $request)


    {

        // return "JHi";
        $user_id = $request->user()->id;
        $user = User::where('id', $user_id)->first();
        $role = RoleUser::select('role_id')->whereIn('role_id', [3, 4, 7])->where('user_id', $user_id)->first();
        // return $role;
        if ($role->role_id == 3) {
            $profile = EmployerProfile::where('user_id', $user_id)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'employer' => $profile
                ],
                'message' => 'Profile Data'
            ], 200);
        } elseif ($role->role_id == 4) {
            $profile = AgentProfile::where('user_id', $user_id)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'agent' => $profile
                ],

                'message' => 'Profile Data'
            ], 200);
        } else {
            $profile = ProfessionalProfile::where('user_id', $user_id)->first();
            $qualification = Qualification::where('user_id', $user_id)->get();
            $experience = ProfessionalExperience::where('user_id', $user_id)->get();
            $profile['qualifications'] = $qualification;
            $profile['experiences'] = $experience;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user, 
                    'professional' => $profile,
                ],
                'message' => 'Profile Data'
            ], 200);
        }
    }
}