<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Intervention\Image\Facades\Image;
use App\Models\User;

class CustomerController extends Controller
{

    public function clientProfileUpdate(Request $request){



        $user_id = auth('sanctum')->user()->id;



        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            
            ]);



            if($validator->fails()){
                return $this->sendError('The email field is required.', $validator->errors());       
            }

        $data= $request->all();
        $email_address= $data['email'];
        
        $countEmail = User::where('id', $user_id)->where('email', $email_address)->get()->count();

        if($countEmail===0){


            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|min:2|max:200',
                'last_name' => 'required|string|min:2|max:200',
                'email' => 'required|email|unique:users',
                
                ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }


            $updateItem = User::where('id', '=',$user_id)->update(['first_name'=> $data['first_name'],'last_name'=> $data['last_name'],
            'email'=> $data['email']]);

          //  return $updateItem;


          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $user_id; $message='Profile updated successfully'; }

          $response = [
              'success' => $success,
              'data'    => $get_id,
              'message' => $message,
          ];
          return response()->json($response, 200);

            }else {



                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string|min:2|max:200',
                    'last_name' => 'required|string|min:2|max:200',
                    'email' => 'required|email',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
    
                    $updateItem = User::where('id', '=',$user_id)->update(['first_name'=> $data['first_name'],'last_name'=> $data['last_name'],
                    'email'=> $data['email']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $user_id; $message='Profile updated successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
        }


    }

    public function profileUpdate(Request $request){


        $user_id = auth('sanctum')->user()->id;



        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            
            ]);



            if($validator->fails()){
                return $this->sendError('The email field is required.', $validator->errors());       
            }

        $data= $request->all();
        $email_address= $data['email'];
        
        $countEmail = User::where('id', $user_id)->where('email', $email_address)->get()->count();

        if($countEmail===0){


            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:200',
                'first_name' => 'required|string|min:2|max:200',
                'last_name' => 'required|string|min:2|max:200',
                'email' => 'required|email|unique:users',
                
                ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }


            $updateItem = User::where('id', '=',$user_id)->update(['name'=> $data['name'],'first_name'=> $data['first_name'],'last_name'=> $data['last_name'],
            'email'=> $data['email']]);

          //  return $updateItem;


          if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $user_id; $message='Profile updated successfully'; }

          $response = [
              'success' => $success,
              'data'    => $get_id,
              'message' => $message,
          ];
          return response()->json($response, 200);

            }else {

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|min:2|max:200',
                    'first_name' => 'required|string|min:2|max:200',
                    'last_name' => 'required|string|min:2|max:200',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
    
                    $updateItem = User::where('id', '=',$user_id)->update(['name'=> $data['name'],'first_name'=> $data['first_name'],'last_name'=> $data['last_name'],
                    'email'=> $data['email']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $user_id; $message='Profile updated successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
        }


    }

        
    public function passwordChange(Request $request){


                $user_id = auth('sanctum')->user()->id;
    
                $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:6|max:50',
                    'c_password' => 'required|same:password',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
                $data= $request->all();
    
                $updateItem = User::where('id', '=',$user_id)->update(['password'=> bcrypt($data['password'])]);
            
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $user_id; $message='Password change successfully'; }
    
                $response = [
                    'success' => $success,
                    'data'    => $get_id,
                    'message' => $message,
                ];
                return response()->json($response, 200);
    
    
            
    }

    public function imageUpload(Request $request){

        // return "Hi";

        $user_id = auth('sanctum')->user()->id;
        

        $savingPath='vender_images/venders';

        $validator = Validator::make($request->all(), [
            'vender_id' => 'required|integer|min:1|max:999999999999',
            'image_name'=>'required|mimes:png,jpg,gif,jpeg|max:8048',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


            $data= $request->all();
            $imageName=$data['image_name'];
            $vender_id=$data['vender_id'];

            // return $user_id;

         //   $foodVenderCount = Food_menu:: where([['id','=',$data['menu_id']], ['user_id','=',$user_id]])->get()->count();

            if($user_id==$vender_id){
 
                $vender_id=$data['vender_id'];
                $maxOriginalNameSize=50;
                //   if(strlen($ImageNameOrg) > $maxOriginalNameSize){ $ImageNewNameSet=substr($ImageNameOrg, -5, $maxOriginalNameSize);
                //       $ImageNewName= $ImageNewNameSet.'.'.$imageName->getClientOriginalExtension();
                //    }
                //   else { $ImageNewName = $ImageNameOrg;}// shorting the image name;
        
                // $imageNewName =  strtotime('Y-m-d H:i:s');
        
                $getImageName = strtotime(date("Y-m-d H:i:s.u")).'.'.$imageName->getClientOriginalExtension();
        
                // return $getImageName;
      
                $newPath= $savingPath.'/'.$user_id;
        
                if (!file_exists($newPath)) {  mkdir($newPath, 0777, true);  }
        
             
                $img = Image::make($imageName)->fit(700, 300, function ($constraint) {
                    $constraint->upsize();
                });
                
                $upload = $img->save($newPath.'/'.$getImageName, 60);
        
                if($upload){
                    $imageSave = User::where("id", $user_id)->update(["banner_image" => $getImageName]);
                }
        
                if(!$upload){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $vender_id; $message='Image uploaded successfully'; }

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

    public function imageDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;
  
       
        $validator = Validator::make($request->all(), [
            'vender_id' => 'required|integer|min:1|max:999999999999',
            'image_name' => 'required|string|min:2|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
            $data= $request->all();
            $vender_id=$data['vender_id'];
            $image_name=$data['image_name'];

                //   return $vender_id;

            // return $vender_id;

            // $venderFind = User::where('id','=', $user_id)->get()->count()
      
            if($vender_id == $user_id){
    
                if($vender_id!=''){
                    $imageSavedPath='vender_images/venders/'.$user_id.'/'. $image_name;

                    if (file_exists($imageSavedPath)) { unlink($imageSavedPath);  }
                    $delete = User::where("id", $user_id)->update(["banner_image" => NULL]);
         
                    if(!$delete){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                    else{   $success=true; $get_id = $vender_id; $message='Image deleted successfully'; }
     
                 }
        
            } else {
                    $success=false;
                    $get_id=$vender_id;
                    $message='Unauthorized action'; 
            }      



        $response = [
            'success' => $success,
            'data'    => $get_id.'-'.$user_id.'-'.$image_name,
            'message' => $message,
        ];

        
        return response()->json($response, 200);


    }

    public function radiusUpdate(Request $request){


                $user_id = auth('sanctum')->user()->id;
    
                $validator = Validator::make($request->all(), [
                    'radius' => 'required|integer|min:0|max:999',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
                $data= $request->all();
    
                $updateItem = User::where('id', '=',$user_id)->update(['search_radius'=> $data['radius']]);
            
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Search radius updated successfully'; }
    
                $response = [
                    'success' => $success,
                    'data'    => $get_id,
                    'message' => $message,
                ];
                return response()->json($response, 200);
    
    
            
    }
        
    public function sendError($error, $errorMessages = [], $code = 202) {
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