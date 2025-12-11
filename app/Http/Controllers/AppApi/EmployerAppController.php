<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Job;
use App\Models\EmployerProfile;
use App\Models\JobDetailsPoint;
use App\Models\JobApplicant;




use DB;
use Validator;



// use App\Http\Resources\JobListingResources;
// use App\Http\Resources\JobBookmarkListResources;
use App\Http\Resources\EmployerJobListingResources;

class EmployerAppController extends Controller
{
    public function employerHome(Request $request)

    
    {

      // return "Hi";

      $user_id = auth('sanctum')->user()->id;

      $jobs = Job::where('worker_type', '0')
      ->where('user_id', '=', $user_id)
      ->where('delete_status', '=', 0)
      ->with('post')
      ->with('employer.company_country_data')
      ->with('jobPointsDescriptions')
      ->with('jobPointsRequirements')
      ->with('jobBookmarks')
      ->with('jobApplicantsAll')
      ->orderBy('id','desc')
      ->get()->take(3);



      $title='Free Sign up';  $subTitle ='Your Future begin here'; $link ='#'; $bgImage ='';

      $bannerInfo = 
      [              
          'title'=>$title,
          'subTitle'=>$subTitle,
          'link'=>$link,
          'bgImage'=>$bgImage,

      ];

      $totalResumes=434;      $totalEmployer=34;      $totalJobPostd=342;     $totalHired=434;

      $summeryInfo = 
      [
          'tResumes'=>$totalResumes+8000,
          'tEmployer'=>$totalJobPostd+150,
          'tJobPosted'=>$totalResumes+1234,
          'tHired'=>$totalHired+2000,

      ];


      

      return response()->json([
          'success' => true,
          // 'data' => $jobs,
          'banner'=> $bannerInfo,
          'summery'=> $summeryInfo,
          'data'=> EmployerJobListingResources::collection($jobs),
          'message' => 'Jobs data fetch success'
      ], 200);

    }

}