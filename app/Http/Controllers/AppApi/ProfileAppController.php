<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Country;
use App\Models\Message;
use DB;

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