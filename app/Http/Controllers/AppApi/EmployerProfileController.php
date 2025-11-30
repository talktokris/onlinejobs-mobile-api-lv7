<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\EmployerProfile;

use DB;
use Validator;
use Intervention\Image\Facades\Image;
class EmployerProfileController extends Controller
{
    
    public function employerAccountProfileUpdate(Request $request)
    {



      $user_id = auth('sanctum')->user()->id;

    

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:1|max:9999999999999999',
          'company_name' => 'required|string|min:1|max:250',
          'company_address' => 'required|string|min:2|max:250',
          'company_country' => 'required|integer|min:1|max:250',
          'company_city' => 'required|string|min:2|max:250',
          'state' => 'required|string|min:2|max:250',
          'company_email' => 'required|email|min:2|max:250',
          'company_phone' => 'required|numeric|min:1|digits_between: 1,99999999999',
          'website' => 'required|string|min:2|max:250',

          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }
      

      $data= $request->all();

    //   return $user_id."-". $data['id'];
      
      $foodVenderCount = EmployerProfile:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
      
    //   return $user_id."-". $data['id']."-".$foodVenderCount;
      
      if($foodVenderCount>=1){


          $updateItem = EmployerProfile::where('id', '=',$data['id'])->update(['company_name'=> $data['company_name'],'company_address'=> $data['company_address'], 'address'=>$data['company_address'],'company_country'=> $data['company_country'], 'country'=> $data['company_country'],'company_city'=> $data['company_city'],'state'=> $data['state'],'company_email'=> $data['company_email'],'company_phone'=> $data['company_phone'],'website'=> $data['website']]);

          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else
          {  
              
              // $updateUser = User::where('id', '=',$user_id)->update(['name'=> $data['name'],'last_name'=> $data['last_name']]);
              // if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
              // else{   $success=true; $get_id = $data['id']; $message='Personal Information updated successfully'; }

              $success=true; $get_id = $data['id']; $message='Employer Information updated successfully';
          }

      } 
      else 
      {
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

    
    public function employerAccountImageUpload(Request $request)
    {

      $user_id = auth('sanctum')->user()->id;
      $savingPath='assets/user_images/';

      $validator = Validator::make($request->all(), [
          'image_name'=>'required|mimes:png,jpg,gif,jpeg|max:8048',
          'id' => 'required|integer|min:1|max:999999999999',
          ]);

      if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());       
      }

          $data= $request->all();
          $imageName=$data['image_name'];
           
          // return $imageName;

          if($user_id==$data['id'])
          {
              $id=$data['id'];
              $maxOriginalNameSize=50;
              $getImageName = strtotime(date("Y-m-d H:i:s.u")).'.'.$imageName->getClientOriginalExtension();
              $newPath= $savingPath.'/'.$user_id;
              if (!file_exists($newPath)) {  mkdir($newPath, 0777, true);  }
      
              $img = Image::make($imageName)->fit(400, 400, function ($constraint) {
                      $constraint->upsize();
              });
              $upload = $img->save($newPath.'/'.$getImageName, 60);
      
              if($upload){
                  $imageSave = EmployerProfile::where("user_id", $data['id'])->update(["company_logo" => $getImageName]);
              }
      
              if(!$upload){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
              else{   $success=true; $get_id = $id; $message='Image uploaded successfully'; }

          } else {
                  $success=false;
                  $get_id=$foodVenderCount;
                  $message='Unauthorized action'; 
          }      

      $response = [
          'success' => $success,
          'data'    => $get_id.'-'.$user_id,
          'message' => $message,
      ];
      return response()->json($response, 200);

    }


    public function employerAccountResumeShortlisted(Request $request)
    {

      return "employerAccountResumeShortlisted";

    }


    public function employerAccountResumeHired(Request $request)
    {

      return "employerAccountResumeHired";

    }

    public function employerAccountSupport(Request $request)
    {

      return "employerAccountAupport";

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