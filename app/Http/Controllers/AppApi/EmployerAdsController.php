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
use Intervention\Image\Facades\Image;

use App\Http\Resources\UserProfileResource;
use App\Http\Resources\EmployerProfileResource;
use App\Http\Resources\EmployerJobListingResources;
use App\Http\Controllers\AppApi\CommanMessageController;


class EmployerAdsController extends Controller
{

    public function employerAdsListing(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $jobs = Job::where('worker_type', '0')
      ->where('user_id','=', $user_id)
      ->where('delete_status', '=', 0)
      ->with('post')
      ->with('employer.company_country_data')
      ->with('jobPointsDescriptions')
      ->with('jobPointsRequirements')
      ->with('jobApplicantsAll.applicantProfile.nationality_data')
      ->orderBy('id','desc')
      ->get()->take(100);

      return response()->json([
          'success' => true,
          // 'data' => $jobs,   
          'data'=> EmployerJobListingResources::collection($jobs),
          'message' => 'Jobs data fetch success'
      ], 200);

    }



    public function employerAdsViewDetails(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'job_id' => 'required|integer|min:1|max:9999999999999999',
      ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

      $data= $request->all();
      $job_id= $data['job_id'];

      //  $jobs = Job::where('id','=', $job_id)

      $jobs = Job::where('id','=', $job_id)
      ->where('user_id','=', $user_id)
      ->where('delete_status', '=', 0)
      ->with('post')
      ->with('employer.company_country_data')
      ->with('jobPointsDescriptions')
      ->with('jobPointsRequirements')
      ->with('jobApplicantsAll.applicantProfile.nationality_data', 'jobApplicantsAll.applicantUser')
      ->orderBy('id','desc')
      ->get();


      return response()->json([
        'success' => true,
        // 'data' => $jobs,   
        'data'=> EmployerJobListingResources::collection($jobs),
        'message' => 'Jobs data fetch success'
      ], 200);

    }

    public function employerAdsViewResumeDetails(Request $request)
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
  
        $user_info = User::where('id','=',$id)->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('pro_experiences.country_name')->with('qualifications.country_name')->with('job_languages')->with('user_skills.skillInfo','user_skills.levelInfo')->with('user_appreciations')->first();
        $userDataSet =  new UserProfileResource($user_info);
  
  
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
  
        return response()->json([
          'success' => true,
          // 'data' => $jobs,   
          'data'=>   new UserProfileResource($user_info),
          'message' => 'Jobs data fetch success'
        ], 200);
  
      

    }



    public function employerAdsCreate(Request $request)
    {

  

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'positions_name' => 'required|integer|min:1|max:9999',
          'job_vacancies_type' => 'required|string|min:2|max:250',
          'total_number_of_vacancies' => 'required|integer|min:1|max:9999',
          'salary_offer' => 'required|string|min:2|max:250',
          'salary_offer_period' => 'required|string|min:2|max:250',
          'working_hours' => 'string|min:2|max:250',
          'gender' => 'string|min:2|max:250',
          'marital_status' => 'string|min:2|max:250',
          'race' =>'required|string|min:2|max:250',
          'academic_field' =>'required|string|min:2|max:250',
          'publish_status' => 'required|string|min:2|max:250',

          ]);
          

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();

     //  $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
          $foodVenderCount=1;
      if($foodVenderCount>=1){
          // return $foodVenderCount;

          $salary_offer_currency ='RM';
          $today =date('Y-m-d');

          $closing_date = date('Y-m-d', strtotime("+30 days"));
        //   return $today.'-'.$closing_date;


          $saveItem = new Job;
          $saveItem->user_id = $user_id;
          $saveItem->positions_name = $data['positions_name'];
          $saveItem->job_vacancies_type = $data['job_vacancies_type'];
          $saveItem->total_number_of_vacancies = $data['total_number_of_vacancies'];
          $saveItem->salary_offer = $data['salary_offer'];
          $saveItem->salary_offer_currency = $salary_offer_currency;
          $saveItem->closing_date = $closing_date;
          $saveItem->salary_offer_period = $data['salary_offer_period'];
          $saveItem->working_hours = $data['working_hours'];
          $saveItem->gender = $data['gender'];
          $saveItem->marital_status = $data['marital_status'];
          $saveItem->race = $data['race'];
          $saveItem->academic_field = $data['academic_field'];
          $saveItem->status = 1;
          $saveItem->worker_type = 0;
          $saveItem->delete_status = 0;
          $saveItem->publish_status = $data['publish_status'];
          
          $saveItem->save();

          if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   
              $success=true; 
              $get_id = $saveItem->id; 
              $message='New Ad created successfully';
              
              // Send push notification to all job seekers when ad is published
              if ($data['publish_status'] == 'Published' || $data['publish_status'] == 'published') {
                  $notificationService = new \App\Services\ExpoNotificationService();
                  $employer = User::find($user_id);
                  $employerName = $employer ? $employer->name : 'An employer';
                  $jobTitle = $data['job_vacancies_type'] ?? 'a new job';
                  
                  $notificationTitle = 'New Job Posted';
                  $notificationBody = $employerName . ' posted ' . $jobTitle;
                  
                  $notificationService->sendToAllJobSeekers($notificationTitle, $notificationBody, [
                      'type' => 'new_ad',
                      'job_id' => $saveItem->id
                  ]);
              }
          }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);

    }

    public function employerApplicationAction(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
          'job_id' => 'required|integer|min:1|max:9999999999999999',
          'status_message' => 'required|string|min:2|max:250',
         
          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();

    //  return $user_id."-".$data['id']."-".$data['job_id'];
      
      $findJobVarificationCount = JobApplicant:: where([['id','=',$data['id']],['job_id','=',$data['job_id']]])->get()->count();
    //   return $findJobVarificationCount;
      $findAdsVarificationCount = Job:: where([['user_id','=',$user_id], ['id','=',$data['job_id']]])->get()->count();
     

      if($findJobVarificationCount > 0 && $findAdsVarificationCount > 0){

   

          $updateItem = JobApplicant::where('id', '=',$data['id'])->update(['selection_status'=> $data['status_message'],'status'=> 1]);

          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Application status updated successfully'; }



            $messageController = new CommanMessageController();
            

            //Massage Saving for User
            $applicantData = JobApplicant:: where([['id','=',$data['id']],['job_id','=',$data['job_id']]])->get()->first();
            $applicantDataID = $applicantData->user_id;

            $setTitle='Your Application is '.$data['status_message'];
            $setMessagText='Dear user, Your Application is '.$data['status_message']. ',You will be information about all the progress.';
            $messageSave = $messageController->messageCreate($applicantDataID, $setTitle, $setMessagText);
            // $users = messageCreate($id=0, $title="", $messageText="");
          
        //Massage Saving for Employer

          $setTitle='Your have '.$data['status_message'] .' an application for futher process.' ;
          $setMessagText='Dear Employer, Your have '.$data['status_message'] .' an application for futher process. Please find all further process applicant list under Account > shortlisted menu.';
          $messageSave = $messageController->messageCreate($user_id, $setTitle, $setMessagText);


      } else {
              $success=false;
              $get_id=0;
              $message='Unauthorized action. Job applicant or job not found.'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);


    }


    public function employerAdsEdit(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
          'positions_name' => 'required|integer|min:1|max:9999',
          'job_vacancies_type' => 'required|string|min:2|max:250',
          'total_number_of_vacancies' => 'required|integer|min:1|max:9999',
          'salary_offer' => 'required|string|min:2|max:250',
          'salary_offer_period' => 'required|string|min:2|max:250',
          'working_hours' => 'string|min:2|max:250',
          'gender' => 'string|min:2|max:250',
          'marital_status' => 'string|min:2|max:250',
          'race' =>'required|string|min:2|max:250',
          'academic_field' =>'required|string|min:2|max:250',
          'publish_status' => 'required|string|min:2|max:250',
          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();
      
      $foodVenderCount = Job:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
      
      if($foodVenderCount>=1){

        $salary_offer_currency ='RM';
        $today =date('Y-m-d');

        $closing_date = date('Y-m-d', strtotime("+30 days"));


          $updateItem = Job::where('id', '=',$data['id'])->update(['positions_name'=> $data['positions_name'],'job_vacancies_type'=> $data['job_vacancies_type'],'total_number_of_vacancies'=> $data['total_number_of_vacancies'],'salary_offer'=> $data['salary_offer'],'salary_offer_currency'=> $salary_offer_currency,'closing_date'=> $closing_date,'salary_offer_period'=> $data['salary_offer_period'],'working_hours'=> $data['working_hours'],'gender'=> $data['gender'],'marital_status'=> $data['marital_status'],'race'=> $data['race'],'academic_field'=> $data['academic_field'],'publish_status'=> $data['publish_status']]);

          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ads updated successfully'; }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);


    }



    public function employerDdsDelete(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
      ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

      $data= $request->all();


      $foodVenderCount = Job:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

      if($foodVenderCount>=1){
          // return $foodVenderCount;
          // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

          $updateItem = Job::where("id", $data['id'])->update(["delete_status" => 1]);
  
          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ad deleted successfully'; }
  
      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }

      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      return response()->json($response, 200);

    }



    public function employerAdsDescriptionAdd(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'job_id' => 'required|integer|min:1|max:9999999999999999',
          'point_details' => 'required|string|min:2|max:250',

          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();

      $foodVenderCount = Job:: where([['id','=',$data['job_id']], ['user_id','=',$user_id]])->get()->count();
          //  $foodVenderCount=1;
      if($foodVenderCount>=1){
          // return $foodVenderCount;

          $saveItem = new JobDetailsPoint;
          $saveItem->user_id = $user_id;
          $saveItem->job_id = $data['job_id'];
          $saveItem->type = 2;
          $saveItem->point_details = $data['point_details'];
          $saveItem->status = 1;
          $saveItem->save();

          if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $saveItem->id; $message='New job description created successfully'; }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);

    }



    public function employerAdsDescriptionEdit(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
        'id' => 'required|integer|min:1|max:9999999999999999',
        'point_details' => 'required|string|min:2|max:250',

        ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();
      
      $foodVenderCount = JobDetailsPoint:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();

      
      if($foodVenderCount>=1){


          $updateItem = JobDetailsPoint::where('id', '=',$data['id'])->update(['point_details'=> $data['point_details']]);

          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ads job requirement updated successfully'; }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);


    }



    public function employerAdsDescriptionDelete(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

    

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
      ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

      $data= $request->all();

      $foodVenderCount = JobDetailsPoint:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

      if($foodVenderCount>=1){
    //   return $user_id ."-a". $data['id'];

          // return $foodVenderCount;
          // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

          $updateItem = JobDetailsPoint::where('id' ,'=', $data['id'])->update(["delete_status" => 1]);
  
          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ad job description deleted successfully'; }
  
      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }

      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      return response()->json($response, 200);

    }



    public function employerAdsRequirementAdd(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'job_id' => 'required|integer|min:1|max:9999999999999999',
          'point_details' => 'required|string|min:2|max:250',

          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();

      $foodVenderCount = Job:: where([['id','=',$data['job_id']], ['user_id','=',$user_id]])->get()->count();
          //  $foodVenderCount=1;
      if($foodVenderCount>=1){
          // return $foodVenderCount;

          $saveItem = new JobDetailsPoint;
          $saveItem->user_id = $user_id;
          $saveItem->job_id = $data['job_id'];
          $saveItem->type = 1;
          $saveItem->point_details = $data['point_details'];
          $saveItem->status = 1;
          $saveItem->save();

          if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $saveItem->id; $message='New job requirement created successfully'; }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);

    }



    public function employerAdsRequirementEdit(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
        'id' => 'required|integer|min:1|max:9999999999999999',
        'point_details' => 'required|string|min:2|max:250',

        ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      
      $data= $request->all();
      
      $foodVenderCount = JobDetailsPoint:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();

      
      if($foodVenderCount>=1){


          $updateItem = JobDetailsPoint::where('id', '=',$data['id'])->update(['point_details'=> $data['point_details']]);

          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ads job requirement updated successfully'; }

      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }
      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
      ];
      
      return response()->json($response, 200);

    }



    public function employerAdsRequirementDelete(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
      ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

      $data= $request->all();


      $foodVenderCount = JobDetailsPoint:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

      if($foodVenderCount>=1){
          // return $foodVenderCount;
          // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

          $updateItem = JobDetailsPoint::where("id", $data['id'])->update(["delete_status" => 1]);
  
          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $data['id']; $message='Ad job requirement deleted successfully'; }
  
      } else {
              $success=false;
              $get_id=$foodVenderCount;
              $message='Unauthorized action'; 
      }

      $response = [
          'success' => $success,
          'data'    => $get_id,
          'message' => $message,
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