<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Job;
use DB;
use App\Models\JobLanguage;
use App\Models\JobAcademic;
use App\Models\JobDetailsPoint;
use App\Models\JobBookmark;
use App\Models\JobApplicant;



use App\Http\Resources\JobListingResources;
use App\Http\Resources\JobBookmarkListResources;

use App\Http\Controllers\AppApi\CommanMessageController;



use Validator;

class JobsAppController extends Controller
{
    //

    public function topHome(Request $request)
    {

        $jobs = Job::where('worker_type', '0')
        ->with('post')
        ->with('employer.company_country_data')
        ->with('jobPointsDescriptions')
        ->with('jobPointsRequirements')
        ->with('jobBookmarks')
        ->with('jobApplicantsAll')
        ->orderBy('id','desc')
        ->get()->take(5);



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
            'data'=> JobListingResources::collection($jobs),
            'message' => 'Jobs data fetch success'
        ], 200);

    }


    public function joblist(Request $request)
    {
        $jobs = Job::where('worker_type', '0')
        ->with('post')
        ->with('employer.country_data')
        ->with('jobPointsDescriptions')
        ->with('jobPointsRequirements')
        ->with('jobBookmarks')
        ->with('jobApplicantsAll')
        ->orderBy('id','desc')
        ->get()->take(100);

        return response()->json([
            'success' => true,
            // 'data' => $jobs,   
            'data'=> JobListingResources::collection($jobs),
            'message' => 'Jobs data fetch success'
        ], 200);

    }

    public function jobsearch(Request $request)
    {

        return "Job Search";

    }


    public function jobApply(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;

        // return $user_id;

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer|min:1|max:999999999999999',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        $job_id =$data['job_id'] ;


        $PreCount = JobApplicant:: where([['job_id','=',$data['job_id']], ['user_id','=',$user_id]])->get()->count();

        // return $job_id;
            // $PreCount=1;
            
        $messageController = new CommanMessageController();

         $employerData = Job:: where('id','=',$job_id)->get()->first();
         $employer_id = $employerData->user_id;
        //  return $employer_id;
         $setTitle='You have received new applicaion advertisement';
         $setMessagText='Dear employer, Your have received new applicaion on your running advertisement. Please findout more about it by our mobile application.';
         $messageSave = $messageController->messageCreate($employer_id, $setTitle, $setMessagText);
         // $users = messageCreate($id=0, $title="", $messageText="");
       
        //Massage Saving for Employer

        $setTitle='You have successfully applied for the job' ;
        $setMessagText='Dear User, Your job application is submited and will be get notify for futher process.';
        $messageSave = $messageController->messageCreate($user_id, $setTitle, $setMessagText);


        if($PreCount==0)
        {

            $saveItem = new JobApplicant;
            $saveItem->user_id = $user_id;
            $saveItem->job_id = $job_id;
            $saveItem->status = 1;
            $saveItem->selection_status='Application Received';
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Job apply added successfully'; }

         //Massage Saving for User
     

         

        } 
        else 
        {

            $success=false;
            $get_id=$PreCount;
            $message='You have already applied this job'; 
     
        }
        
        $response = 
        [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function jobBookmark(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer|min:1|max:999999999999999',
 
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        $job_id =$data['job_id'];

        $PreCount = JobBookmark:: where([['job_id','=',$data['job_id']], ['user_id','=',$user_id]])->get()->count();

        // return $PreCount;
            // $PreCount=1;
        if($PreCount==0)
        {

            $saveItem = new JobBookmark;
            $saveItem->user_id = $user_id;
            $saveItem->job_id = $job_id;
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Bookmark added successfully'; }

            $messageController = new CommanMessageController();
            $setTitle='Your have successfully bookmark an adertisemt.' ;
            $setMessagText='Dear User, Your have successfully Bookmark an advertisement. Please find all bookmarked advertisement list under Account > Bookmarks menu.';
            $messageSave = $messageController->messageCreate($user_id, $setTitle, $setMessagText);

        } 
        else 
        {

            $PreCountUpdate = JobBookmark:: where([['job_id','=',$data['job_id']], ['user_id','=',$user_id], ['delete_status','=',1]])->get()->count();
            if($PreCountUpdate>=1){ $delete_status = 0; $statusMessageText = 'Undeleted';} else { $delete_status=1; $statusMessageText = 'Deleted';}

            $updateItem = JobBookmark::where([['job_id','=',$data['job_id']], ['user_id','=',$user_id]])->update(['delete_status'=> $delete_status]);
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else
            {   
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['job_id'].'-'.$delete_status.'-'.$statusMessageText; $message='Bookmark removed successfully'; }
            }
     
        }
        
        $response = 
        [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);

        
    }

    public function jobBookmarkList(Request $request)
    {

        $user_id = auth('sanctum')->user()->id;

        
        // $data= $request->all();
        // // $job_id =$data['job_id'];

        $jobBookMarkData = JobBookmark:: where('user_id','=',$user_id)->where('delete_status','=',0)->with('job_details.post','job_details.employer.country_data','job_details.jobPointsDescriptions','job_details.jobPointsRequirements','job_details.jobBookmarks','job_details.jobApplicantsAll')->orderBy('id','desc')
        ->get()->take(100);;


        return response()->json([
            'success' => true,
            // 'data' => $jobBookMarkData,   
            // 'data'=> JobListingResources::collection($jobBookMarkData),
             'data'=> JobBookmarkListResources::collection($jobBookMarkData),
            'message' => 'Jobs bookmark data fetch success'
        ], 200);

        
        return response()->json($response, 200);

        
    }
    
    public function JobAppliedList(Request $request)
    {

        $user_id = auth('sanctum')->user()->id;


        $jobBookMarkData = JobApplicant:: where('user_id','=',$user_id)->where('delete_status','=',0)->with('job_details.post','job_details.employer.country_data','job_details.jobPointsDescriptions','job_details.jobPointsRequirements','job_details.jobBookmarks','job_details.jobApplicantsAll')->orderBy('id','desc')
        ->get()->take(100);;


        return response()->json([
            'success' => true,
            // 'data' => $jobBookMarkData,   
            // 'data'=> JobListingResources::collection($jobBookMarkData),
             'data'=> JobBookmarkListResources::collection($jobBookMarkData),
            'message' => 'Jobs Applied data fetch success'
        ], 200);

        
        return response()->json($response, 200);

        
    }
    
    public function sendError($error, $errorMessages = [], $code = 202) 
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    } 
    
}