<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentProfile;
use Validator;
use App\Models\User;

class PartnerController extends Controller
{
    public function updateProfile(Request $request, $id)
    {
        $valid = Validator::make($request->all(),[
             'per_email' => 'required'
        ]);
        if($valid->fails())
        {
            return response()->json([
                'success'=>false,
                'error'=>$valid->errors()
            ],401);
        }else{
            $user = User::where('id', $id)->first();
            $user->name = $request->first_name;
            $user->email = $request->per_email;
            $user->phone = $request->agency_phone;
            $user->save();

            //return $agent;
            $agent_profile = $user->agent_profile;
            $agent_profile->agency_registered_name = $request->agency_registered_name;
            $agent_profile->agency_registration_no=$request->agency_registration_no;
            $agent_profile->license_no = $request->license_no;
            $agent_profile->agency_country = $request->agency_country??$request->per_country;
            $agent_profile->agency_state = $request->agency_state ?? $request->per_state;
            $agent_profile->agency_city = $request->agency_city ?? $request->per_city;
            $agent_profile->agency_address = $request->agency_address;
            $agent_profile->agency_phone = $request->agency_phone;
            // $agent_profile->agency_fax = $request->agency_fax;
            $agent_profile->agency_email = $request->email ?? $request->per_email;
        
            $agent_profile->license_issue_date = $request->license_issue_date;
            $agent_profile->license_expire_date = $request->license_expire_date;

            // if($request->file('license_file')){
            //     $this->validate($request, [
            //         'license_file' => 'mimes:pdf,jpg,jpeg,png|max:1024',
            //     ]);
                
            //     $image_basename = explode('.',$request->file('license_file')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('license_file')->getClientOriginalExtension();

            //     $request->license_file->storeAs('public', $image);

            //     // $img = Image::make($request->file('license_file')->getRealPath());
            //     // $img->stream();

            //     // //Upload image
            //     // Storage::disk('local')->put('public/'.$image, $img);

            //     //remove existing file
            //     if($agent_profile->license_file != ''){
            //         Storage::disk('local')->delete('public/'.$agent_profile->license_file);
            //     }
            //     //add new image path to database
            //     $agent_profile->license_file = $image;
                
            // }
            // if($request->file('passport_file')){
            //     $this->validate($request, [
            //         'passport_file' => 'mimes:pdf,jpg,jpeg,png|max:1024',
            //     ]);
            //     $image_basename = explode('.',$request->file('passport_file')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('passport_file')->getClientOriginalExtension();

            //     $request->passport_file->storeAs('public', $image);
            //     // $img = Image::make($request->file('passport_file')->getRealPath());
            //     // $img->stream();

            //     // //Upload image
            //     // Storage::disk('local')->put('public/'.$image, $img);

            //     //remove existing file
            //     if($agent_profile->passport_file != ''){
            //         Storage::disk('local')->delete('public/'.$agent_profile->passport_file);
            //     }
            //     //add new image path to database
            //     $agent_profile->passport_file = $image;
                
            // }
            //Point of Contact
            $agent_profile->first_name = $request->first_name;
            // $agent_profile->middle_name = $request->middle_name;
            $agent_profile->last_name = $request->last_name;
            $agent_profile->contact_phone = $request->contact_phone;
            // $agent_profile->contact_phone2 = $request->contact_phone2;
            // $agent_profile->passport = $request->passport;
            // $agent_profile->contact_email = $request->contact_email;
            
            // $agent_profile->address = $request->per_address;
            // $agent_profile->designation = $request->designation;
            // $agent_profile->nationality = $request->nationality;
            $agent_profile->save();
            return response()->json([
                'success'=>true,
                'message'=>'Partner Profile Updated Successfully'
            ],200);
        }
        
    }
}