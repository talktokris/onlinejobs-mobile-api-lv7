<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Applicant;
use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\UserProfile;
use DB;
use App\Models\Country;
use App\Models\Profiles;
use Carbon\Carbon;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;


class DemandController extends Controller
{
    // use AuthenticatesUsers;
    use RegistersUsers;

    public function getEmployersDemandDataForAgent(Request $request)
    {
        $user_id = $request->user()->id;
        $demands = DB::table('offers')
            ->leftjoin('users', 'users.id', '=', 'offers.employer_id')
            ->leftjoin('applicants', 'applicants.offer_id', '=', 'offers.id')
            ->select('offers.id', 'offers.company_name', 'offers.preferred_country as nationality', 'users.name as personIncharge', 'offers.expexted_date as ejd', 'offers.demand_qty as NoofWorker', 'applicants.proposed', 'applicants.confirmed', 'applicants.finalized', 'offers.status', 'offers.proposed_date')
            ->where('assigned_agent', $user_id)
            ->get();
        return response()->json([
            'success' => true,
            'data' => $demands,
            'message' => 'Worker Demand list for Agents'
        ], 200);
    }
    public function getEmployersDemandDetailsForAgent($id)
    {
        $demandsDetail = Offer::whereIn('status', [2, 3, 4, 5, 6, 7])->where('id', $id)->first();
        return response()->json([
            'success' => isset($demandsDetail),
            'data' => $demandsDetail,
            'message' => 'Worker Demand Details list for Agent'
        ], 200);
    }

    public function getEmployersDemandWorkersForAgent($demand_id)
    {

        $users = DB::table('users')
            ->leftjoin('applicants', 'applicants.user_id', 'users.id')
            ->leftjoin('profiles', 'profiles.user_id', 'users.id')
            ->select('users.id', 'profiles.image', 'users.name', 'profiles.passport_number', 'profiles.nationality', 'profiles.date_of_birth', 'profiles.marital_status', 'applicants.status')
            ->where('applicants.offer_id', $demand_id)
            ->where('users.status', '1')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Worker Demand Details list for Agent'
        ], 200);
    }

    public function getEmployersDemandData(Request $request)
    {
        $user_id = $request->user()->id;
        $demands = DB::table('offers')
            ->leftjoin('users', 'users.id', '=', 'offers.employer_id')
            ->leftjoin('applicants', 'applicants.offer_id', '=', 'offers.id')
            ->select('offers.id', 'offers.company_name', 'offers.preferred_country as nationality', 'users.name as personIncharge', 'offers.expexted_date as ejd', 'offers.demand_qty as NoofWorker', 'applicants.proposed', 'applicants.confirmed', 'applicants.finalized', 'offers.status', 'offers.proposed_date')
            ->where('employer_id', $user_id)
            ->get();
        return response()->json([
            'success' => true,
            'data' => $demands,
            'message' => 'Worker Demand list for Agents'
        ], 200);
    }

    public function getEmployersDemandDetails($id)
    {
        $demandsDetail = Offer::whereIn('status', [2, 3, 4, 5, 6, 7])->where('id', $id)->first();
        return response()->json([
            'success' => isset($demandsDetail),
            'data' => $demandsDetail,
            'message' => 'Worker Demand Details list for Agent'
        ], 200);
    }

    public function getEmployersDemandWorkers($demand_id)
    {

        $users = DB::table('users')
            ->leftjoin('applicants', 'applicants.user_id', 'users.id')
            ->leftjoin('profiles', 'profiles.user_id', 'users.id')
            ->select('users.id', 'profiles.image', 'users.name', 'profiles.passport_number', 'profiles.nationality', 'profiles.date_of_birth', 'profiles.marital_status', 'applicants.status')
            ->where('applicants.offer_id', $demand_id)
            ->where('users.status', '1')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Worker Demand Details list for Agent'
        ], 200);
    }

    public function saveDemand(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'job_position' => 'required',
            'expexted_date' => 'required',
            'preferred_country' => 'required',
            'demand_qty' => 'required',
            // 'IssueDate' => 'date',
            // 'DemandFile' => 'mimes:pdf,jpg,jpeg,png|max:1024',
            // 'approvalQuotaAndLevy' => 'mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 422);
        } else {

            $offer = new Offer;
            $offer->employer_id = auth()->user()->id;
            $user_id = auth()->user()->id;
            $employer = EmployerProfile::where('user_id', $user_id)->first();
            $offer->title = 'Demand Letter';
            $offer->company_name = $employer->company_name;
            $offer->issue_date = Carbon::now();
            $offer->status = 2;

            $offer->job_position = $request->job_position;
            $offer->job_location_state = $request->job_location_state;
            $offer->job_location_city = $request->job_location_city;
            $offer->job_location = $request->job_location;
            $offer->gender = $request->gender;
            $offer->marital_status = $request->marital_status;
            $offer->preferred_language = $request->preferred_language;
            $offer->highest_education = $request->highest_education;
            $offer->reading = $request->reading;
            $offer->written = $request->written;
            $offer->comments = $request->comments;
            $offer->expexted_date = $request->expexted_date;
            $offer->preferred_country = $request->preferred_country;
            $offer->demand_qty = $request->demand_qty;

            // $offer->hiring_package = $request->HiringPackage;

            // $offer->company_name = $request->CompanyName;
            // $offer->demand_letter_no = $request->DemandLetterNo;

            // if($request->file('DemandFile')){            
            //     $file_basename = explode('.',$request->file('DemandFile')->getClientOriginalName())[0];
            //     $file_name = $file_basename . '-' . time() . '.' . $request->file('DemandFile')->getClientOriginalExtension();

            //     $request->DemandFile->storeAs('public/demand_letter', $file_name);
            //     //add new image path to database
            //     $offer->demand_file = $file_name;

            // }
            // if($request->file('approvalQuotaAndLevy')){            
            //     $file_basename = explode('.',$request->file('approvalQuotaAndLevy')->getClientOriginalName())[0];
            //     $file_name = $file_basename . '-' . time() . '.' . $request->file('approvalQuotaAndLevy')->getClientOriginalExtension();

            //     $request->approvalQuotaAndLevy->storeAs('public/demand_letter', $file_name);
            //     //add new image path to database
            //     $offer->approvalQuotaAndLevy = $file_name;

            $offer1 = $offer->replicate();
            $offer2 = $offer->replicate();
            $offer->save();

            if ($request->preferred_country2) {
                $offer1->preferred_country = $request->preferred_country2;
                $offer1->demand_qty = $request->demand_qty2;
                $offer1->save();
            }

            if ($request->preferred_country3) {
                $offer2->preferred_country = $request->preferred_country3;
                $offer2->demand_qty = $request->demand_qty3;
                $offer2->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Demand sent successfully!'
            ], 200);
            // }

            /*Validation*/
            // $this->validate($request, [
            //     'IssueDate' => 'date',
            //     // 'ExpectedJoinDate' => 'date',
            // ]);

            // if($request->file('DemandFile')){
            //     $this->validate($request, [
            //         'DemandFile' => 'mimes:pdf,jpg,jpeg,png|max:1024',
            //     ]);
            // }
            // if($request->file('approvalQuotaAndLevy')){
            //     $this->validate($request, [
            //         'approvalQuotaAndLevy' => 'mimes:pdf,jpg,jpeg,png|max:1024',
            //     ]);
            // }



            //Send notification to the Admins
            // $admins = User::whereRoleIs('superadministrator')->get();
            // $data = $offer;
            // Notification::send($admins, new DemandLetterSent($data));
        }
    }
}