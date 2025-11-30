<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Job;
use App\Models\EmployerProfile;
use App\Models\JobDetailsPoint;
use App\Models\ResumeBookmark;
use App\Models\Profile;

use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\EmployerUserProfileResource;




class EmployerResumeController extends Controller
{
   
    public function employerResumeBookmarks(Request $request)
    {

    //   return "employerResumeSearch";
   
        $user_id = auth('sanctum')->user()->id;




    // $data= User::where('status', '=', 1)

    $data= User::where('id', '>', 1)

        ->whereHas('resume_bookmarks', function($q) use ($user_id) {
            $q->when($user_id, function($q) use ($user_id) {
                $q->where('employer_id', '=',   $user_id );
              
            });

           
        })
                      
         ->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('pro_experiences.country_name')->with('qualifications.country_name')->with('job_languages')->with('user_skills.skillInfo','user_skills.levelInfo')->with('user_appreciations')->with('resume_bookmarks')
         ->orderBy('id', 'DESC')
         ->get();

            
        //          

    //   return $data;


      return response()->json([
        'success' => true,
        // 'data' => $jobs,   
        'data'=>    EmployerUserProfileResource::collection($data),
        'message' => 'Jobs data fetch success'
      ], 200);

  

    }

   
    public function employerResumeSearch(Request $request)
    {

    //   return "employerResumeSearch";
   
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
           // 'gender' => 'required|integer|min:1|max:9999999999999999',
            'search_word' => 'nullable|string|min:1|max:100',
            'country' => 'required', Rule::exists('id', 'title'),   
            'education' => 'required', Rule::exists('id', 'title'),
            'gender' => 'required', Rule::exists('id', 'title'),
            'religion' => 'required', Rule::exists('id', 'title'),
            'marital_status' => 'required', Rule::exists('id', 'title'),
            'skill' => 'required', Rule::exists('id', 'title'),
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

    $data= $request->all();
    // json_decode($jsonobj)
    $searchWordData=$data['search_word'];

    $countryDataJson = json_encode($data['country']);
    $countryData = json_decode($countryDataJson);
    $educationDataJson=json_encode($data['education']);
    $educationData = json_decode($educationDataJson);

    $genderDataJson=json_encode($data['gender']);
    $genderData = json_decode($genderDataJson);

    $religionDataJson=json_encode($data['religion']);
    $religionData = json_decode($religionDataJson);

    $maritalStatusDataJson=json_encode($data['marital_status']);
    $maritalStatusData = json_decode($maritalStatusDataJson);

    $skillDataJson=json_encode($data['skill']);
    $skillData = json_decode($skillDataJson);




     

    $country_id_value="";
    $education_id_value="";
    $gender_id_value="";
    $religion_id_value="";
    $marital_status_id_value="";
    $skill_id_value="";

    // return $country_id_value;


    if($countryData->title !="Any"){ $country_id_value=$countryData->id ;}
    if($educationData->title !="Any"){ $education_id_value=$educationData->id ;}
    if($genderData->title !="Any"){ $gender_id_value=$genderData->id ;}
    if($religionData->title !="Any"){ $religion_id_value=$religionData->id ;}
    if($maritalStatusData->title !="Any"){ $marital_status_id_value=$maritalStatusData->id ;}
    if($skillData->title !="Any"){ $skill_id_value=$skillData->id ;}

    // return $country_id_value;
    // return $education_id_value;
    // return $gender_id_value;
    // return $religion_id_value;
    // return $marital_status_id_value;
    // return $skill_id_value;



    //return $countryData->title;

    $searchWord= $data['search_word'];
    $escaped = '%' . $searchWord . '%';

    $genderId= $gender_id_value; //genderId=2 ;
    $nationalityId= $country_id_value; // $nationalityId = 4;
    $religionId= $religion_id_value; // $religionId=4;
    $maritalStatusId= $marital_status_id_value; //  $maritalStatusId=3;
    $education=$education_id_value;   //  $education="BE"
    $skillId=$skill_id_value;  



    // $data= User::where('status', '=', 1)

    $data= User::where('id', '>', 1)

        ->whereHas('user_profile_info', function($q) use ($genderId, $nationalityId, $religionId, $maritalStatusId) {
            $q->when($genderId, function($q) use ($genderId) {
                $q->where('gender', '=',   $genderId );
              
            });

            $q->when($nationalityId, function($q) use ($nationalityId) {
                $q->where('nationality', '=',   $nationalityId );
              
            });

            $q->when($religionId, function($q) use ($religionId) {
                $q->where('religion', '=',   $religionId );
              
            });

            $q->when($maritalStatusId, function($q) use ($maritalStatusId) {
                $q->where('marital_status', '=',   $maritalStatusId );
              
            });
        })

        ->whereHas('education_profile', function($q) use ($education, $escaped) {
            $q->when($education, function($q) use ($education) {
               $q->where('subject', '=',   $education );
              
            });

            $q->when($escaped, function($q) use ($escaped) {
                $q->where('subject', 'LIKE',   $escaped );
                $q->orWhere('qualification', 'LIKE', $escaped);
                $q->orWhere('specialization', 'LIKE', $escaped);
               
             });


        })

        ->whereHas('user_skills', function($q) use ($skillId,) {
            $q->when($skillId, function($q) use ($skillId) {
               $q->where('skill_id', '=',   $skillId );
              
            });


        })
        
                        
         ->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('pro_experiences.country_name')->with('qualifications.country_name')->with('job_languages')->with('user_skills.skillInfo','user_skills.levelInfo')->with('user_appreciations')->with('resume_bookmarks')
         ->orderBy('id', 'DESC')
         ->get();

            
        //          

    //   return $data;


      return response()->json([
        'success' => true,
        // 'data' => $jobs,   
        'data'=>    EmployerUserProfileResource::collection($data),
        'message' => 'Jobs data fetch success'
      ], 200);

  

    }



    public function employerResumeView(Request $request)
    {


      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'user_id' => 'required|integer|min:1|max:9999999999999999',
      ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

      $data= $request->all();
      $id= $data['user_id'];

      $user_info = User::where('id','=',$id)->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('pro_experiences.country_name')->with('qualifications.country_name')->with('job_languages')->with('user_skills.skillInfo','user_skills.levelInfo')->with('user_appreciations')->with('resume_bookmarks')->first();
    //   $userDataSet =  new EmployerUserProfileResource($user_info);


      //  $jobs = Job::where('id','=', $job_id)
/*
      $jobs = Job::where('id','=', $job_id)->where('user_id','=', $user_id)
      ->with('post')
      ->with('employer.company_country_data')
      ->with('jobPointsDescriptions')
      ->with('jobPointsRequirements')
      ->with('jobApplicantsAll.applicantProfile.nationality_data', 'jobApplicantsAll.applicantUser')
      ->orderBy('id','desc')
      ->get();
*/

// return $user_info;
      return response()->json([
        'success' => true,
        // 'data' => $jobs,   
        'data'=>   new EmployerUserProfileResource($user_info),
        'message' => 'Jobs data fetch success'
      ], 200);

    


    }

    

    public function employerResumeContactView(Request $request)
    {

      return "employerResumeContactView";

    }



    public function employerResumeSelectAction(Request $request)
    {

        $employer_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1|max:999999999999999',
 
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        $user_id =$data['user_id'];

        $PreCount = ResumeBookmark:: where([['employer_id','=',$employer_id], ['user_id','=',$user_id]])->get()->count();

        // return $PreCount;
            // $PreCount=1;
        if($PreCount==0)
        {

            $saveItem = new ResumeBookmark;
            $saveItem->user_id = $user_id;
            $saveItem->employer_id = $employer_id;
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $statusMessageText='Bookmark added successfully'; }

        
        } 
        else 
        {

            $PreCountUpdate = ResumeBookmark:: where([['employer_id','=',$employer_id], ['user_id','=',$user_id], ['delete_status','=',1]])->get()->count();
            if($PreCountUpdate>=1){ $delete_status = 0; $statusMessageText = 'Bookmark added successfully';} else { $delete_status=1; $statusMessageText = 'Bookmark removed successfully';}

            $updateItem = ResumeBookmark::where([['employer_id','=',$employer_id], ['user_id','=',$user_id]])->update(['delete_status'=> $delete_status]);
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else
            {   
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $employer_id.'-'.$delete_status.'-'.$statusMessageText; $message='Bookmark removed successfully'; }
            }
     
        }
        
        $response = 
        [
            'success' => $success,
            'data'    => $get_id,
            'message' => $statusMessageText,
        ];
        
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