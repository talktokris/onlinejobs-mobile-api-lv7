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

class EmployerMsgController extends Controller
{


    public function employerMessageThreadListing(Request $request)
    {

      return "employerMessageThreadListing";

    }


    public function employerMessageThreadView(Request $request)
    {

      return "employerMessageThreadView";

    }


    public function employerMessageThreadCreate(Request $request)
    {

      return "employerMessageThreadCreate";

    }

    public function employerMessageThreadReply(Request $request)
    {

      return "employerMessageThreadReply";

    }

    public function employerMessages(Request $request)
    {
             $user_id = auth('sanctum')->user()->id;
            //  return $user_id;


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



    public function employerMessageReadCount(Request $request){
        
        
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

  public function employerMessageReadUpdate(Request $request){
      
      
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