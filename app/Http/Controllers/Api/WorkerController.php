<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use AApp\Modelspp\AgentProfile;
use DB;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use Validator;
use App\Models\Skill;
use Illuminate\Support\Facades\Hash;
use App\Models\Country;


class WorkerController extends Controller
{
    public function getAgentForeignWorkers(Request $request)
    {
        $user_id = $request->user()->id;
        $agent_code = AgentProfile::where('user_id', $user_id)->pluck('agent_code');
        $foreignWorker = DB::table('profiles')
            ->leftjoin('agent_profiles', 'agent_profiles.agent_code', '=', 'profiles.agent_code')
            ->leftjoin('countries', 'countries.id', '=', 'profiles.nationality')
            ->leftjoin('users', 'users.id', '=', 'profiles.user_id')
            ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftjoin('applicants', 'applicants.user_id', '=', 'users.id')
            ->select('applicants.updated_at', 'applicants.status', 'profiles.name', 'profiles.image', 'applicants.id as app_id', 'profiles.agent_code', 'agent_profiles.agency_registered_name', 'profiles.passport_number as passport', 'countries.name as country', 'users.public_id', 'users.id', 'users.code', 'users.email', 'users.created_at', 'users.name as user_name')
            ->where('users.status', '=', 1)
            ->where('role_user.role_id', '=', 5)
            ->where('profiles.agent_code', '=', $agent_code)
            ->get();
        return response()->json([
            'success' => true,
            'data' => $foreignWorker,
            'message' => 'Foreign Worker list for Agent'
        ], 200);
    }
    public function ForeignWorkerDetail($id)
    {
        $profile = Profile::where('user_id', $id)->first();
        $experiences = Experience::where('user_id', $id)->get();
        $educations = Education::where('user_id', $id)->get();
        return response()->json([
            'success' => true,
            'data' => [
                'profile' => $profile,
                'experiences' => $experiences,
                'educations' => $educations
            ],
            'message' => 'Foreign Worker list for Agent'
        ], 200);
    }

    public function addForeignWorker(Request $request)
    {
        $valid = Validator::make($request->all(), [
            // name,
            // dateOfBirth,
            // contactNo,
            // gender,
            // country,
            // state,
            // city,
            // address,
            // nationality,
            // halfImage,
            // emergencyContactName,
            // emergencyContactRelationship,
            // emergencyContactNo,
            // emergencyContactAddress,
            // passportNumber,
            // passportIssuePlace,
            // passportIssueDate,
            // passportExpiryDate,
            // passportCopy,
        ]);

        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'error' => $valid->errors()
            ], 401);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email ?? time() . '@test.com';
            $user->phone = $request->phone;
            $user->password = Hash::make('password');
            $user->public_id = time() . md5($user->email);
            $user->status = 1;
            $role = 'worker';

            // $skills = Skill::where('status', '=', 1)->where('for', 'gw')->where('type','Skill')->get();
            // $languages = Skill::where('status', '=', 1)->where('for', 'gw')->where('type','Language')->get();

            $user_country = $request->nationality;
            $user->code = $this->user_code($user_country);
            $user->save();
            $user->attachRole($role);

            $profile = new Profile;
            // Personal Information
            $profile->name = $request->name;
            $profile->date_of_birth = $request->date_of_birth;
            $profile->phone = $request->phone;
            $profile->gender = $request->gender;
            $profile->email = $request->email;
            $profile->marital_status = $request->marital_status;
            $profile->children = $request->children;
            $profile->siblings = $request->siblings;
            $profile->country = $request->company_country;
            $profile->state = $request->company_state;
            $profile->city = $request->company_city;
            $profile->district = $request->district;
            $profile->address = $request->address;
            $profile->nationality = $request->nationality;
            $profile->religion = $request->religion;
            $profile->height = $request->height;
            $profile->weight = $request->weight;
            $profile->father_name = $request->father_name;
            $profile->mother_name = $request->mother_name;
            $profile->father_contact_number = $request->father_contact_number;
            $profile->sector_id = $request->sector;
            $profile->sub_sector_id = $request->sub_sector;

            // Emergency Contact
            $profile->emergency_contact_name = $request->emergency_contact_name;
            $profile->emergency_contact_relationship = $request->emergency_contact_relationship;
            $profile->emergency_contact_phone = $request->emergency_contact_phone;
            $profile->emergency_contact_address = $request->emergency_contact_address;

            /*Passport Info*/
            $profile->passport_number = $request->passport_number;
            $profile->passport_issue_date = $request->passport_issue_date;
            $profile->passport_issue_place = $request->passport_issue_place;
            $profile->passport_expire_date = $request->passport_expire_date;

            $profile->user_id = $user->id;
            $profile->other_skills = $request->other_skills;
            $profile->agent_code = $request->agent_code;

            // foreach($skills as $skill){
            //     $skill_arr[$skill->slug] = request($skill->slug) ?? 'No';
            // }
            // $profile->skill_set = json_encode($skill_arr);

            // foreach($languages as $language){
            //     $lang_arr[$language->slug] = request($language->slug) ?? 'No';
            // }
            // $profile->language_set = json_encode($lang_arr);

            $profile->save();

            // Education
            // if($request->education_level && $request->education_level[0] != null){
            //     for($i=0; $i< count($request->education_level); $i++){
            //         $education = new Education;
            //         $education->user_id = $user->id;
            //         $education->education_level = $request->education_level[$i];
            //         $education->education_remark = $request->education_remark[$i];
            //         $education->save();
            //     }
            // }
            // Experience
            // if($request->employer_name && $request->employer_name[0] != null){
            //     for($i=0; $i< count($request->employer_name); $i++){
            //         $experience = new Experience;
            //         $experience->user_id = $user->id;
            //         $experience->employer_name = $request->employer_name[$i];
            //         $experience->country = $request->country[$i];
            //         $experience->from_date = $request->from_date[$i];
            //         $experience->to_date = $request->to_date[$i];
            //         $experience->remark = $request->remark[$i];
            //         $experience->save();
            //     }
            // }
            // if($request->file('image')){
            //     $image_basename = explode('.',$request->file('image')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            //     $img = Image::make($request->file('image')->getRealPath());
            //     $img->stream();

            //     //Upload image
            //     Storage::disk('local')->put('public/'.$image, $img);

            //     //Remove if there was any old image
            //     if($profile->image != ''){
            //         Storage::disk('local')->delete('public/'.$profile->image);
            //     }

            //     //add new image path to database
            //     $profile->image = $image;

            // }

            // if($request->file('full_image')){
            //     $image_basename = explode('.',$request->file('full_image')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('full_image')->getClientOriginalExtension();

            //     $img = Image::make($request->file('full_image')->getRealPath());
            //     $img->stream();

            //     //Upload image
            //     Storage::disk('local')->put('public/'.$image, $img);

            //     //Remove if there was any old image
            //     if($profile->full_image != ''){
            //         Storage::disk('local')->delete('public/'.$profile->full_image);
            //     }

            //     //add new image path to database
            //     $profile->full_image = $image;

            // }

            // if($request->file('passport_file')){            
            //     $image_basename = explode('.',$request->file('passport_file')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('passport_file')->getClientOriginalExtension();

            //     $request->passport_file->storeAs('public', $image);
            //     // $img = Image::make($request->file('passport_file')->getRealPath());
            //     // $img->stream();

            //     // //Upload image
            //     // Storage::disk('local')->put('public/'.$image, $img);

            //     // //Remove if there was any old image
            //     // if($profile->passport_file != ''){
            //     //     Storage::disk('local')->delete('public/'.$profile->passport_file);
            //     // }

            //     //add new image path to database
            //     $profile->passport_file = $image;

            // }

            // if($request->file('medical_certificate')){          
            //     $image_basename = explode('.',$request->file('medical_certificate')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('medical_certificate')->getClientOriginalExtension();

            //     $request->medical_certificate->storeAs('public', $image);
            //     //$request->file('medical_certificate')->move('storage/public', $request->file('medical_certificate')->getRealPath());
            //     // $img = Image::make($request->file('medical_certificate')->getRealPath());
            //     // $img->stream();

            //     // //Upload image
            //     // Storage::disk('local')->put('public/'.$image, $img);

            //     // //Remove if there was any old image
            //     // if($profile->medical_certificate != ''){
            //     //     Storage::disk('local')->delete('public/'.$profile->medical_certificate);
            //     // }

            //     //add new image path to database
            //     $profile->medical_certificate = $image;

            // }

            // if($request->file('immigration_security_clearence')){
            //     $image_basename = explode('.',$request->file('immigration_security_clearence')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('immigration_security_clearence')->getClientOriginalExtension();

            //     $request->immigration_security_clearence->storeAs('public', $image);
            //     // $img = Image::make($request->file('immigration_security_clearence')->getRealPath());
            //     // $img->stream();

            //     // //Upload image
            //     // Storage::disk('local')->put('public/'.$image, $img);

            //     // //Remove if there was any old image
            //     // if($profile->immigration_security_clearence != ''){
            //     //     Storage::disk('local')->delete('public/'.$profile->immigration_security_clearence);
            //     // }

            //     //add new image path to database
            //     $profile->immigration_security_clearence = $image;

            // }

            return response()->json([
                'success' => true,
                'message' => 'Foreign Worker added successfully!'
            ], 200);
        }
    }

    public function user_code($country_id)
    {
        $country = Country::where('id', $country_id)->first();
        for ($i = 1; $i < 10000; $i++) {
            if ($i < 10) {
                //00009
                $j = '0000' . $i;
            } elseif ($i >= 10 && $i < 100) {
                //00099
                $j = '000' . $i;
            } elseif ($i >= 100 && $i < 1000) {
                //00999
                $j = '00' . $i;
            } elseif ($i >= 1000 && $i < 10000) {
                //09999
                $j = '0' . $i;
            } else {
                //99999
                $j = $i;
            }
            $user_code = $country->code . $j;
            if (!User::where('code', '=', $user_code)->exists()) {
                break;
            }
        }
        return $user_code;
    }
}