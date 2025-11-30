<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\EmployerProfile;
use App\Models\Job;
use DB;
use Validator;
use App\Models\User;


class EmployerController extends Controller
{

    public function index()
    {
        $employer=EmployerProfile::all();
    }

    public function show($id)
    {
        // return "aaa";

        return response()->json([
            'success'=>'true',
            'data'=> Offer::whereIn('status', [2, 3, 4, 5, 6, 7])->where('employer_id', $id)->orderBy('created_at', 'desc')->get(),
            'message'=>'Employer data fetch success'
        ],200);
    }

    public function companies()
    {
        $employer=EmployerProfile::all()->take(10);
        $companyJobsCount=DB::table('employer_profiles')
                    ->leftjoin('jobs','jobs.user_id','=','employer_profiles.user_id')
                    ->select('jobs.id','jobs.positions_name','jobs.worker_type','jobs.total_number_of_vacancies','jobs.closing_date','employer_profiles.company_name','employer_profiles.id as employer_id','employer_profiles.company_logo')
                    ->where('jobs.worker_type','0')
                    ->count();
        return response()->json([
            'success'=>true,
            'data'=> [
                'employer'=>$employer,
                'companyJobsCount'=>$companyJobsCount
            ],
            'message'=>'Employer data fetch success'
        ],200);
    }

    public function companyDetail($id)
    {
        $employerDetail=EmployerProfile::where('id',$id)->get();
        return response()->json([
            'success'=>true,
            'data'=> $employerDetail,
            'message'=>'Employer Detail fetch success'
        ],200);
    }

    public function companyJobs($id)
    {
        $companyjobs=DB::table('employer_profiles')
                    ->leftjoin('jobs','jobs.user_id','=','employer_profiles.user_id')
                    ->select('jobs.id','jobs.positions_name','jobs.worker_type','jobs.total_number_of_vacancies','jobs.closing_date','employer_profiles.company_name','employer_profiles.id as employer_id','employer_profiles.company_logo')
                    ->where('jobs.worker_type','0')
                    ->where('jobs.user_id',$id)
                    ->get()->take(10);
        return response()->json([
            'success'=>true,
            'data'=> $companyjobs,
            'message'=>'Company Jobs data fetch success'
        ],200);
    }

    public function updateProfile(Request $request, $id)
    {
        $valid = Validator::make($request->all(),[
            // 'company_name' => 'required'
        ]);
        if($valid->fails())
        {
            return response()->json([
                'success'=>false,
                'error'=>$valid->errors()
            ],401);
        }else{
            $employer = User::where('id', $id)->first();
            $employer_profile = $employer->employer_profile;

            $employer->name = $request->name;
            $employer->email = $request->email;
            $employer->phone = $request->phone;

            $employer->save();

            $employer_profile->nric = $request->nric;
            //$employer_profile->address = $request->address;
            $employer_profile->country = $request->country;
            $employer_profile->contact_email = $request->contact_email;

            $employer_profile->company_name = $request->company_name;
            $employer_profile->company_phone = $request->company_phone;
            $employer_profile->website = $request->website;
            $employer_profile->roc = $request->roc;
            $employer_profile->company_address = $request->company_address;
            $employer_profile->postcode = $request->postcode;
            $employer_profile->company_country= $request->company_country;
            $employer_profile->state= $request->company_state;
            if(empty($request->company_city)){
                $employer_profile->company_city="N/A";
            }
            else{
                $employer_profile->company_city= $request->company_city;
            }
            $employer_profile->looking_for_pro = $request->looking_for_pro ?? null;
            $employer_profile->looking_for_gw = $request->looking_for_gw ?? null;
            $employer_profile->looking_for_dm = $request->looking_for_dm ?? null;
            $employer_profile->looking_for_rp = $request->looking_for_rp ?? null;
            // if($request->file('company_logo')){
            //     $image_basename = explode('.',$request->file('company_logo')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('company_logo')->getClientOriginalExtension();

            //     $request->company_logo->storeAs('public', $image);
            //     // dd($request->company_logo->storeAs('public', $image));

            //     //add new image path to database
            //     $employer_profile->company_logo = $image;
                
            // }

            // if($request->file('work_place_img')){
            //     $image_basename = explode('.',$request->file('work_place_img')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('work_place_img')->getClientOriginalExtension();

            //     $request->work_place_img->storeAs('public', $image);
            //     // dd($request->company_logo->storeAs('public', $image));

            //     //add new image path to database
            //     $employer_profile->work_place_img = $image;
                
            // }
            // if($request->file('nature_of_work')){
            //     $image_basename = explode('.',$request->file('nature_of_work')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('nature_of_work')->getClientOriginalExtension();

            //     $request->nature_of_work->storeAs('public', $image);
            //     // dd($request->company_logo->storeAs('public', $image));

            //     //add new image path to database
            //     $employer_profile->nature_of_work_img = $image;
                
            // }

            // if($request->file('hostel_img')){
            //     $image_basename = explode('.',$request->file('hostel_img')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('hostel_img')->getClientOriginalExtension();

            //     $request->hostel_img->storeAs('public', $image);
            //     // dd($request->company_logo->storeAs('public', $image));

            //     //add new image path to database
            //     $employer_profile->hostel_img = $image;
                
            // }


            // if($request->file('product_of_company')){
            //     $image_basename = explode('.',$request->file('product_of_company')->getClientOriginalName())[0];
            //     $image = $image_basename . '-' . time() . '.' . $request->file('product_of_company')->getClientOriginalExtension();

            //     $request->product_of_company->storeAs('public', $image);
            //     // dd($request->company_logo->storeAs('public', $image));

            //     //add new image path to database
            //     $employer_profile->product_of_company_img = $image;
                
            // }

            $employer_profile->save();
            return response()->json([
                'success'=>true,
                'message'=>'Employer Profile Updated Successfully'
            ],200);
        }
    }
}