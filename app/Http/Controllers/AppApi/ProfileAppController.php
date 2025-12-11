<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Country;
use App\Models\Message;
use App\Models\UserSkill;
use App\Models\JobLanguage;
use App\Models\UserAppreciation;
use App\Models\Qualification;
use App\Models\ProfessionalExperience;
use App\Models\ResumeBookmark;
use App\Models\JobBookmark;
use App\Models\Applicant;
use App\Models\JobApplicant;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Profile;
use App\Models\UserProfile;
use App\Models\EmployerProfile;
use App\Models\AgentProfile;
use App\Models\ProfessionalProfile;
use App\Models\RetiredPersonnel;
use App\Models\RetiredPersonnelsLanguage;
use App\Models\RetiredPersonnelsWorkExperience;
use App\Models\RetiredPersonnelEducation;
use App\Models\Maid;
use App\Models\PartTimeEmployer;
use App\Models\RoleUser;
use DB;
use Illuminate\Support\Facades\Log;

use Validator;
use App\Http\Resources\MessageResource;

use App\Http\Controllers\AppApi\FillsAppController;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\EmployerProfileResource;





class ProfileAppController extends Controller
{
    public function userProfile(Request $request)
    {

        $id = auth('sanctum')->user()->id;
        $role = auth('sanctum')->user()->role_id;

       

        if($role==1){
       
            $user_info = User::where('id','=',$id)->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('pro_experiences.country_name')->with('qualifications.country_name')->with('job_languages')->with('user_skills.skillInfo','user_skills.levelInfo')->with('user_appreciations')->first();
            $userDataSet =  new UserProfileResource($user_info);

        }

        if($role==2){
  
            $user_info = User::where('id','=',$id)->with('applicants')->with('job_bookmarks')->with('user_profile_info')->with('employer_profile.company_country_data')->first();
            $userDataSet =  new EmployerProfileResource($user_info);
        }

        // return  $user_info;

        // $user_info = User::where('id',$id)->with('user_skills.skillInfo','user_skills.levelInfo')->get();


        // $user_skills = \DB::table('user_skills as us')
       
        // ->join('skills as s', 's.id', '=', 'us.skill_id')
        // ->join('skill_levels as l', 'l.id', '=', 'us.level_id')
        // ->orderBy('us.id','desc')
        // ->select(
        //     'us.id as id',
        //     'us.skill_id as skill_id',
        //     's.name as skill_name',
        //     's.type as skill_type',
        //     'us.level_id as level_id',
        //     'l.name as level_name',
        //     'us.delete_status as delete_status',
        //     'us.status as status',
            
        // )
        // ->where('us.user_id',$id)
        
        // ->get();


        // $recent_jobs = \DB::table('user_skills')
        // ->where('id',$id)->get();
        // ->select(
        //     'user_skills.id',
        //     'user_skills.skill_id',
        //     'skills.name as skill_name',
        //     'skills.type as skill_type',
        //     'user_skills.level_id as level_id',
        //     'skill_levels.name as level_name',
        //     'user_skills.delete_status as delete_status',
        //     'user_skills.status as status',
            
        // )
        // ->join('skills', 'skills.id', '=', 'user_skills.skill_id')
        // ->join('skill_levels', 'skill_levels.id', '=', 'user_skills.level_id')
        // ->orderBy('user_skills.id','desc')
        // ->get();
  
  
  
      // return $user_info;
  
       //return UserProfileResource::collection($user_info);
  
  
       //$country= Country::get();
       //$user_info= User::get();
  
  
       $statusObj = new FillsAppController;
       $stausMsg =$statusObj->status();
  
  
  
       
          return response()->json([
              'status' => 'success',
              'results' => $userDataSet,
            //   'results' => new UserProfileResource($user_info),
              'options' => $stausMsg,
            
          ]);
    }


    public function userBookmarks(Request $request)
    {

    }

    public function userAppliedJobs(Request $request)
    {

    }

    public function userChangePassword(Request $request)
    {

    }


    public function userSupportInfo(Request $request)
    {

    }

    public function userMessages(Request $request)
    {
             $user_id = auth('sanctum')->user()->id;


            //  $user_id=222;
   
           $messageData = Message::where('user_id','=', $user_id)->orderBy('id', 'DESC')->limit(200)->get();
   
           $status= true;
           $message = 'Message fetched successfully';
   
           
           $response = [
               'status' => $status,
               'data'    => MessageResource::collection($messageData),
               'message' => $message,
           ];
           return response()->json($response, 200);

    }



    public function userMessageReadCount(Request $request){
        
        
      $user_id = auth('sanctum')->user()->id;
   //  $user_id=222;

      $messageDataCount = Message::where('user_id','=', $user_id)->where('read_status','=', 0)->orderBy('id', 'DESC')->limit(200)->get()->count();

      $status= true;
      $message = 'Message fetched successfully';

      
      $response = [
          'status' => $status,
          'data'    => $messageDataCount,
          'message' => $message,
      ];
      return response()->json($response, 200);
      
  }

  public function userMessageReadUpdate(Request $request){
      
      
      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:0|max:9999999',
          
          ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

          $data= $request->all();
          $message_id= $data['id'];

          $updateItem = Message::where('id', '=',$message_id)->update(['read_status'=> 1]);

          if(!$updateItem){   $success=false;   $get_id = $message_id; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $message_id; $message='Message Seen successfully'; }

          $response = [
              'success' => $success,
              'data'    => $get_id,
              'message' => $message,
          ];
          return response()->json($response, 200);
      
  }

  public function deleteAccount(Request $request)
  {
      // Validate confirmation text
      $validator = Validator::make($request->all(), [
          'confirmation_text' => 'required|string|in:Delete',
      ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors(), 400);       
      }

      // Security: Only allow authenticated user to delete their own account
      // The token is tied to a specific user, so auth('sanctum')->user() ensures
      // the user can only delete the account associated with their token
      $user = auth('sanctum')->user();
      
      if (!$user) {
          return $this->sendError('Unauthorized. Please login to continue.', [], 401);
      }

      // Explicitly use only the authenticated user's ID from the token
      // Ignore any user_id that might be passed in the request for security
      $user_id = $user->id;
      
      // Additional security check: Verify the user exists and is active
      $userExists = User::where('id', $user_id)->first();
      if (!$userExists) {
          return $this->sendError('User account not found.', [], 404);
      }

      try {
          DB::beginTransaction();

          // Delete user_skills
          UserSkill::where('user_id', $user_id)->delete();

          // Delete job_languages
          JobLanguage::where('user_id', $user_id)->delete();

          // Delete user_appreciations
          UserAppreciation::where('user_id', $user_id)->delete();

          // Delete qualifications
          Qualification::where('user_id', $user_id)->delete();

          // Delete professional_experiences
          ProfessionalExperience::where('user_id', $user_id)->delete();

          // Delete resume_bookmarks
          ResumeBookmark::where('user_id', $user_id)->delete();

          // Delete job_bookmarks
          JobBookmark::where('user_id', $user_id)->delete();

          // Delete applicants
          Applicant::where('user_id', $user_id)->delete();

          // Delete job_applicants
          JobApplicant::where('user_id', $user_id)->delete();

          // Delete experiences
          Experience::where('user_id', $user_id)->delete();

          // Delete educations
          Education::where('user_id', $user_id)->delete();

          // Delete profile
          Profile::where('user_id', $user_id)->delete();

          // Delete user_profile
          UserProfile::where('user_id', $user_id)->delete();

          // Delete employer_profile (if exists)
          EmployerProfile::where('user_id', $user_id)->delete();

          // Delete agent_profile (if exists)
          AgentProfile::where('user_id', $user_id)->delete();

          // Delete professional_profile (if exists)
          ProfessionalProfile::where('user_id', $user_id)->delete();

          // Delete retired_personnel and related
          $retiredPersonnel = RetiredPersonnel::where('user_id', $user_id)->first();
          if ($retiredPersonnel) {
              RetiredPersonnelsLanguage::where('retired_personnel_id', $retiredPersonnel->id)->delete();
              RetiredPersonnelsWorkExperience::where('retired_personnel_id', $retiredPersonnel->id)->delete();
              RetiredPersonnelEducation::where('retired_personnel_id', $retiredPersonnel->id)->delete();
              $retiredPersonnel->delete();
          }

          // Delete part_time_maid (if exists)
          Maid::where('user_id', $user_id)->delete();

          // Delete part_time_employer (if exists)
          PartTimeEmployer::where('user_id', $user_id)->delete();

          // Delete messages (where user is sender, receiver, or user_id matches)
          Message::where('user_id', $user_id)
                 ->orWhere('sender_id', $user_id)
                 ->orWhere('receiver_id', $user_id)
                 ->delete();

          // Delete role_user (pivot table)
          RoleUser::where('user_id', $user_id)->delete();

          // Delete Sanctum tokens (all tokens for this authenticated user)
          $user->tokens()->delete();

          // Security: Delete only the authenticated user's account
          // $user is the authenticated user from the token, ensuring
          // no one can delete another user's account
          $deletedUserId = $user->id;
          $deletedUserEmail = $user->email;
          $user->delete();
          
          // Log account deletion for security audit
          Log::info('Account deleted', [
              'user_id' => $deletedUserId,
              'email' => $deletedUserEmail,
              'deleted_at' => now(),
          ]);

          DB::commit();

          return response()->json([
              'success' => true,
              'message' => 'Account deleted successfully'
          ], 200);

      } catch (\Exception $e) {
          DB::rollBack();
          Log::error('Account deletion error: ' . $e->getMessage());
          
          return $this->sendError(
              'Failed to delete account. Please try again or contact support.',
              ['error' => $e->getMessage()],
              500
          );
      }
  }

  public function sendError($error, $errorMessages = [], $code = 404) 
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