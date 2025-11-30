<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAppreciation;
use App\Models\JobLanguage;
use App\Models\UserSkill;
use App\Models\Qualification;
use App\Models\ProfessionalExperience;
use App\Models\Profile;
use App\Models\User;

use DB;

use Validator;
use Intervention\Image\Facades\Image;

class ResumeAppController extends Controller
{


    public function personalInfoUpdate(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'name' => 'required|string|min:1|max:250',
            'last_name' => 'required|string|min:2|max:250',
            'date_of_birth' => 'required|string|min:2|max:20',
            // 'nationality' => 'required|integer|min:2|max:250',
            'gender' => 'required|integer|min:1|max:90000',
            'marital_status' => 'required|integer|min:1|max:90000',
            'religion' => 'required|integer|min:1|max:90000',
            'height' => 'required|string|min:2|max:80',
            'weight' => 'required|string|min:2|max:20',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = Profile:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = Profile::where('id', '=',$data['id'])->update(['name'=> $data['name'],'date_of_birth'=> $data['date_of_birth'],'gender'=> $data['gender'],'marital_status'=> $data['marital_status'],'religion'=> $data['religion'],'height'=> $data['height'],'weight'=> $data['weight']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else
            {  
                
                $updateUser = User::where('id', '=',$user_id)->update(['name'=> $data['name'],'last_name'=> $data['last_name']]);
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Personal Information updated successfully'; }
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



    public function contactInfoUpdate(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'email' => 'required|email',
            'mobileNo' => 'required|string|min:4|max:15',
            'address' => 'required|string|min:2|max:250',
            'country' => 'required|integer|min:2|max:250',
            'state' => 'required|string|min:2|max:250',
            'district' => 'required|string|min:2|max:250',
            'city' => 'required|string|min:2|max:250',
            'address' => 'required|string|min:2|max:250',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
/*

        $validatorNo = Validator::make($request->all(), [
            'mobileNo' => 'required|string|min:4|max:15|unique:users,phone,'.$user_id,
        ]);
        
        */
        $data= $request->all();
        
        $foodVenderCount = Profile:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = Profile::where('id', '=',$data['id'])->update(['email'=> $data['email'],'phone'=> $data['mobileNo'],'country'=> $data['country'],'state'=> $data['state'],'district'=> $data['district'],'city'=> $data['city'],'address'=> $data['address']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else
            {   
                $updateUser = User::where('id', '=',$user_id)->update(['email'=> $data['email'],'country_id'=> $data['country']]);
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Contact Information updated successfully'; }
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



    public function resumeImageUpload(Request $request){


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

             

            //  return $user_id."-". $data['id'];

            $foodVenderCount = Profile:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();

            if($foodVenderCount>=1){
 
                $id=$data['id'];
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
        
                $img = Image::make($imageName)->fit(400, 400, function ($constraint) {
                        $constraint->upsize();
                });
                $upload = $img->save($newPath.'/'.$getImageName, 60);
        
                if($upload){
                    $imageSave = Profile::where("id", $data['id'])->update(["image" => $getImageName]);
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

    public function resumeImageDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;
       
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            'image_name' => 'required|string|min:2|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
            $data= $request->all();
            $id=$data['id'];
            $image_name=$data['image_name'];
      
            $foodVenderCount = Profile:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();
            if($foodVenderCount>=1){
    
                if($id!=''){
                    $imageSavedPath='assets/user_images/'.$user_id.'/'. $image_name;

                    if (file_exists($imageSavedPath)) { unlink($imageSavedPath);  }
                    $delete = Profile::where("id", $id)->update(["image" => NULL]);
         
                    if(!$delete){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                    else{   $success=true; $get_id = $id; $message='Image deleted successfully'; }
     
                }
        
            } else {
                    $success=false;
                    $get_id=$foodVenderCount;
                    $message='Unauthorized action'; 
            }      



        $response = [
            'success' => $success,
            'data'    => $get_id.'-'.$user_id.'-'.$image_name,
            'message' => $message,
        ];

        
        return response()->json($response, 200);


    }



    public function addWorkEx(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'country' => 'required|integer|min:1|max:9999',
            'designation' => 'required|string|min:2|max:250',
            'company' => 'required|string|min:2|max:250',
            'from' => 'required|string|min:2|max:250',
            'to' => 'required|string|min:2|max:250',
            'experience_description' => 'string|min:2|max:2000',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        // $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
            $foodVenderCount=1;
        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new ProfessionalExperience;
            $saveItem->user_id = $user_id;
            $saveItem->country = $data['country'];
            $saveItem->designation = $data['designation'];
            $saveItem->company = $data['company'];
            $saveItem->from = $data['from'];
            $saveItem->to = $data['to'];
            $saveItem->experience_description = $data['experience_description'];
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Experience added successfully'; }

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

    public function editWorkEx(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'country' => 'required|integer|min:1|max:9999',
            'designation' => 'required|string|min:2|max:250',
            'company' => 'required|string|min:2|max:250',
            'from' => 'required|string|min:2|max:250',
            'to' => 'required|string|min:2|max:250',
            'experience_description' => 'string|min:2|max:2000',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = ProfessionalExperience:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = ProfessionalExperience::where('id', '=',$data['id'])->update(['country'=> $data['country'],'designation'=> $data['designation'],'company'=> $data['company'],'from'=> $data['from'],'to'=> $data['to'],'experience_description'=> $data['experience_description']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Experience updated successfully'; }

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

    public function deleteWorkEx(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = ProfessionalExperience:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = ProfessionalExperience::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Experience deleted successfully'; }
    
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




    public function addEducation(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'country' => 'required|integer|min:1|max:9999',
            'qualification' => 'required|string|min:2|max:250',
            'subject' => 'required|string|min:2|max:250',
            'specialization' => 'required|string|min:2|max:250',
            'university' => 'required|string|min:2|max:250',
            'join_year' => 'required|integer|min:1|max:9999',
            'passing_year' => 'required|integer|min:1|max:9999',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        // $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
            $foodVenderCount=1;
        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new Qualification;
            $saveItem->user_id = $user_id;
            $saveItem->country = $data['country'];
            $saveItem->qualification = $data['qualification'];
            $saveItem->subject = $data['subject'];
            $saveItem->specialization = $data['specialization'];
            $saveItem->university = $data['university'];
            $saveItem->join_year = $data['join_year'];
            $saveItem->passing_year = $data['passing_year'];
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Education added successfully'; }

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

    public function updateEducation(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'country' => 'required|integer|min:1|max:9999',
            'qualification' => 'required|string|min:2|max:250',
            'subject' => 'required|string|min:2|max:250',
            'specialization' => 'required|string|min:2|max:250',
            'university' => 'required|string|min:2|max:250',
            'join_year' => 'required|integer|min:1|max:9999',
            'passing_year' => 'required|integer|min:1|max:9999',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = Qualification:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = Qualification::where('id', '=',$data['id'])->update(['country'=> $data['country'],'qualification'=> $data['qualification'],'subject'=> $data['subject'],'specialization'=> $data['specialization'],'university'=> $data['university'],'join_year'=> $data['join_year'],'passing_year'=> $data['passing_year']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Education updated successfully'; }

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

    public function deleteEducation(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = Qualification:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = Qualification::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Education deleted successfully'; }
    
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




    public function addSkill(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'skill_id' => 'required|integer|min:1|max:99999',
            'level_id' =>  'required|integer|min:1|max:99999',
            'year' => 'required|string|min:1|max:250',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        // $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
            $foodVenderCount=1;
        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new UserSkill;
            $saveItem->user_id = $user_id;
            $saveItem->skill_id = $data['skill_id'];
            $saveItem->level_id = $data['level_id'];
            $saveItem->year = $data['year'];
            $saveItem->status =1;
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Skill added successfully'; }

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

    public function updateSkill(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'skill_id' => 'required|integer|min:1|max:99999',
            'level_id' =>  'required|integer|min:1|max:99999',
            'year' => 'required|string|min:1|max:250',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = UserSkill:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = UserSkill::where('id', '=',$data['id'])->update(['skill_id'=> $data['skill_id'],'level_id'=> $data['level_id'],'year'=> $data['year']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Skill updated successfully'; }

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

    public function deleteSkill(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = UserSkill:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = UserSkill::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Skill deleted successfully'; }
    
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




    public function addLanguage(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'language' => 'required|string|min:1|max:250',
            'speaking' => 'required|string|min:1|max:150',
            'reading' => 'required|string|min:2|max:250',
            'writing' => 'required|string|min:2|max:150',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        // $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
            $foodVenderCount=1;
        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new JobLanguage;
            $saveItem->user_id = $user_id;
            $saveItem->job_id = 0;
            $saveItem->language = $data['language'];
            $saveItem->speaking = $data['speaking'];
            $saveItem->reading = $data['reading'];
            $saveItem->writing = $data['writing'];
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Language added successfully'; }

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

    public function updateLanguage(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'language' => 'required|string|min:1|max:250',
            'speaking' => 'required|string|min:1|max:150',
            'reading' => 'required|string|min:2|max:250',
            'writing' => 'required|string|min:2|max:150',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = JobLanguage:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = JobLanguage::where('id', '=',$data['id'])->update(['language'=> $data['language'],'speaking'=> $data['speaking'],'reading'=> $data['reading'],'writing'=> $data['writing']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Language updated successfully'; }

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

    public function deleteLanguage(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = JobLanguage:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = JobLanguage::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Language deleted successfully'; }
    
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




    public function addAppreciation(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:150',
            'organization' => 'required|string|min:2|max:250',
            'month' => 'required|string|min:2|max:150',
            'year' => 'required|integer|min:1|max:9999',

            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        // $foodVenderCount = UserAppreciation:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();
            $foodVenderCount=1;
        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new UserAppreciation;
            $saveItem->user_id = $user_id;
            $saveItem->name = $data['name'];
            $saveItem->organization = $data['organization'];
            $saveItem->month = $data['month'];
            $saveItem->year = $data['year'];
            $saveItem->status = 1;
            $saveItem->delete_status = 0;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Appreciation added successfully'; }

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

    public function updateAppreciation(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'name' => 'required|string|min:1|max:150',
            'organization' => 'required|string|min:2|max:250',
            'month' => 'required|string|min:2|max:150',
            'year' => 'required|integer|min:1|max:9999',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = UserAppreciation:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = UserAppreciation::where('id', '=',$data['id'])->update(['name'=> $data['name'],'organization'=> $data['organization'],'month'=> $data['month'],'year'=> $data['year']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Appreciation updated successfully'; }

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

    public function deleteAppreciation(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = UserAppreciation:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = UserAppreciation::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Appreciation deleted successfully'; }
    
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