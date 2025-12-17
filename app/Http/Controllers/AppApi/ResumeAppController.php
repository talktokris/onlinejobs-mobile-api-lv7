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

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ResumeAppController extends Controller
{
    /**
     * Get the correct public path for image uploads
     * Handles both server deployment and local development
     * 
     * @param string $subPath The subdirectory path (e.g., 'assets/user_images/')
     * @return string The full path to the public directory
     */
    private function getPublicUploadPath($subPath = 'assets/user_images/')
    {
        // Server deployment path (production)
        $serverPublicPath = '/home/no47agyrt0nt/public_html/mobile-api/' . $subPath;
        
        // Local development path
        $localPublicPath = public_path($subPath);
        
        // Use server path if it exists, otherwise use local path
        if (file_exists('/home/no47agyrt0nt/public_html/mobile-api/')) {
            return $serverPublicPath;
        }
        
        return $localPublicPath;
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'data' => $errorMessages,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, 200);
    }

    public function personalInfoUpdate(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
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

            // Update Profile table
            $updateProfile = Profile::where('id', '=',$data['id'])->update([
                'name'=> $data['name'],
                'date_of_birth'=> $data['date_of_birth'],
                'gender'=> $data['gender'],
                'marital_status'=> $data['marital_status'],
                'religion'=> $data['religion'],
                'height'=> $data['height'],
                'weight'=> $data['weight']
            ]);

            // Update User table with firstName and lastName
            $updateUser = User::where('id', '=', $user_id)->update([
                'name'=> $data['name'],
                'last_name'=> $data['last_name']
            ]);

            $success=true;
            $get_id=$data['id'];
            $message='Personal information updated successfully';

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

    public function contactInfoUpdate(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'email' => 'required|email|min:2|max:250',
            'mobileNo' => 'required|string|min:2|max:20',
            'country' => 'required|integer|min:1|max:999999999999',
            'city' => 'required|string|min:2|max:250',
            'address' => 'required|string|min:2|max:500',
            'district' => 'required|string|min:2|max:250',
            'state' => 'required|string|min:2|max:250',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = Profile:: where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($foodVenderCount>=1){


            $updateItem = Profile::where('id', '=',$data['id'])->update([
                'email'=> $data['email'],
                'phone'=> $data['mobileNo'],
                'country'=> $data['country'],
                'city'=> $data['city'],
                'address'=> $data['address'],
                'district'=> $data['district'],
                'state'=> $data['state']
            ]);

            $success=true;
            $get_id=$data['id'];
            $message='Contact information updated successfully';

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
        $savingPath = $this->getPublicUploadPath('assets/user_images/');
        
        // Log the base path for debugging
        Log::info('Image upload started', [
            'user_id' => $user_id,
            'public_path' => public_path(),
            'saving_path' => $savingPath,
            'saving_path_exists' => file_exists($savingPath),
            'saving_path_writable' => is_writable($savingPath),
        ]);

        $validator = Validator::make($request->all(), [
            'image_name' => 'required|mimes:png,jpg,jpeg|max:5120', // 5MB max
            'id' => 'required|integer|min:1|max:999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try {
            $data = $request->all();
            $imageName = $data['image_name'];

            // Verify user owns this profile
            $profile = Profile::where([['id', '=', $data['id']], ['user_id', '=', $user_id]])->first();

            if(!$profile){
                return $this->sendError('Unauthorized action.', ['error' => 'Profile not found or access denied']);
            }

            // Check file size (5MB = 5242880 bytes)
            if($imageName->getSize() > 5242880){
                return $this->sendError('File too large.', ['error' => 'Image must be less than 5MB']);
            }

            // Generate unique filename
            $getImageName = time() . '_' . uniqid() . '.' . $imageName->getClientOriginalExtension();
            
            // Ensure parent directory exists with proper permissions
            if (!file_exists($savingPath)) {
                if (!mkdir($savingPath, 0755, true)) {
                    Log::error('Failed to create parent directory: ' . $savingPath);
                    return $this->sendError('Server error.', ['error' => 'Failed to create upload directory. Please contact support.']);
                }
                // Set permissions on parent directory (775 for web server write access)
                @chmod($savingPath, 0775);
            } else {
                // Ensure parent directory is writable
                if (!is_writable($savingPath)) {
                    @chmod($savingPath, 0775);
                }
            }
            
            // Create user directory if it doesn't exist (use DIRECTORY_SEPARATOR for cross-platform compatibility)
            $newPath = $savingPath . DIRECTORY_SEPARATOR . $user_id;
            if (!file_exists($newPath)) {
                if (!mkdir($newPath, 0775, true)) {
                    Log::error('Failed to create user directory: ' . $newPath);
                    return $this->sendError('Server error.', ['error' => 'Failed to create user directory. Please contact support.']);
                }
            } else {
                // Ensure user directory is writable
                if (!is_writable($newPath)) {
                    @chmod($newPath, 0775);
                    if (!is_writable($newPath)) {
                        Log::error('User directory is not writable: ' . $newPath);
                        return $this->sendError('Server error.', ['error' => 'Upload directory is not writable. Please contact support.']);
                    }
                }
            }

            // Delete old image if exists
            if($profile->image && file_exists($newPath . DIRECTORY_SEPARATOR . $profile->image)){
                @unlink($newPath . DIRECTORY_SEPARATOR . $profile->image);
            }

            // Process and save image (200x200 as per UI requirements)
            $img = Image::make($imageName);
            
            // Resize maintaining aspect ratio, fit to 200x200
            $img->fit(200, 200, function ($constraint) {
                $constraint->upsize();
            });
            
            $fullImagePath = $newPath . DIRECTORY_SEPARATOR . $getImageName;
            $upload = $img->save($fullImagePath, 85); // 85% quality

            // Verify file was actually saved
            if($upload && file_exists($fullImagePath)){
                // Set file permissions (644 = readable by all, writable by owner)
                @chmod($fullImagePath, 0644);
                
                // Verify file permissions were set correctly
                $actualPerms = substr(sprintf('%o', fileperms($fullImagePath)), -4);
                $fileSize = filesize($fullImagePath);
                $isReadable = is_readable($fullImagePath);
                
                // Update profile with new image name
                $profile->image = $getImageName;
                $profile->save();

                // Build image URL - use asset() helper for proper URL generation
                $imageUrl = asset('assets/user_images/' . $user_id . '/' . $getImageName);
                
                // Also build the expected URL that mobile app will use
                $expectedUrl = url('assets/user_images/' . $user_id . '/' . $getImageName);
                $appUrl = env('APP_URL', 'https://onlinejobs.my/mobile-api');
                $mobileAppUrl = rtrim($appUrl, '/') . '/assets/user_images/' . $user_id . '/' . $getImageName;

                // Log successful upload for debugging with extensive details
                Log::info('Image uploaded successfully', [
                    'user_id' => $user_id,
                    'profile_id' => $profile->id,
                    'image_name' => $getImageName,
                    'file_path' => $fullImagePath,
                    'file_exists' => file_exists($fullImagePath),
                    'file_size' => $fileSize,
                    'file_permissions' => $actualPerms,
                    'file_readable' => $isReadable,
                    'image_url_asset' => $imageUrl,
                    'image_url_url' => $expectedUrl,
                    'image_url_mobile_app' => $mobileAppUrl,
                    'public_path' => public_path(),
                    'app_url_env' => env('APP_URL'),
                ]);

                $response = [
                    'success' => true,
                    'data' => [
                        'id' => $profile->id,
                        'image' => $getImageName,
                        'image_name' => $getImageName, // Add image_name for compatibility
                        'image_url' => $imageUrl,
                    ],
                    'message' => 'Image uploaded successfully!',
                ];
                return response()->json($response, 200);
            } else {
                Log::error('Image save failed or file not found after save', [
                    'user_id' => $user_id,
                    'file_path' => $fullImagePath,
                    'save_result' => $upload,
                    'file_exists' => file_exists($fullImagePath)
                ]);
                return $this->sendError('Upload failed.', ['error' => 'Failed to save image. Please try again.']);
            }

        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Server error.', ['error' => $e->getMessage()]);
        }
    }

    public function resumeImageDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;
        $savingPath = $this->getPublicUploadPath('assets/user_images/');
       
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try {
            $data = $request->all();
            
            // Verify user owns this profile
            $profile = Profile::where([['id', '=', $data['id']], ['user_id', '=', $user_id]])->first();

            if(!$profile){
                return $this->sendError('Unauthorized action.', ['error' => 'Profile not found or access denied']);
            }

            // Delete image file if exists
            $newPath = $savingPath . '/' . $user_id;
            if($profile->image && file_exists($newPath . '/' . $profile->image)){
                @unlink($newPath . '/' . $profile->image);
            }

            // Update profile to remove image reference
            $profile->image = null;
            $profile->save();

            $response = [
                'success' => true,
                'data' => [
                    'id' => $profile->id,
                ],
                'message' => 'Image deleted successfully!',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Image delete error: ' . $e->getMessage());
            return $this->sendError('Server error.', ['error' => $e->getMessage()]);
        }
    }

    public function addWorkEx(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'designation' => 'required|string|min:2|max:250',
            'company' => 'required|string|min:2|max:250',
            'country' => 'required|integer|min:1|max:999999999999',
            'from' => 'required|string|min:2|max:20',
            'to' => 'required|string|min:2|max:20',
            'experience_description' => 'nullable|string|max:2000',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        try {
            $saveItem = new ProfessionalExperience;
            $saveItem->user_id = $user_id;
            $saveItem->designation = $data['designation'];
            $saveItem->company = $data['company'];
            $saveItem->country = $data['country'];
            $saveItem->from = $data['from'];
            $saveItem->to = $data['to'];
            $saveItem->experience_description = $data['experience_description'] ?? '';
            $saveItem->save();

            if($saveItem->id){
                $success = true;
                $get_id = $saveItem->id;
                $message = 'Work experience added successfully';
            } else {
                $success = false;
                $get_id = 0;
                $message = 'Failed to save work experience';
            }
        } catch (\Exception $e) {
            Log::error('Add work experience error: ' . $e->getMessage());
            $success = false;
            $get_id = 0;
            $message = 'Server error: ' . $e->getMessage();
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function editWorkEx(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'designation' => 'required|string|min:2|max:250',
            'company' => 'required|string|min:2|max:250',
            'country' => 'required|integer|min:1|max:999999999999',
            'from' => 'required|string|min:2|max:20',
            'to' => 'required|string|min:2|max:20',
            'experience_description' => 'nullable|string|max:2000',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $experienceCount = ProfessionalExperience::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($experienceCount >= 1){
            try {
                $updateItem = ProfessionalExperience::where('id', '=', $data['id'])->update([
                    'designation' => $data['designation'],
                    'company' => $data['company'],
                    'country' => $data['country'],
                    'from' => $data['from'],
                    'to' => $data['to'],
                    'experience_description' => $data['experience_description'] ?? '',
                ]);

                $success = true;
                $get_id = $data['id'];
                $message = 'Work experience updated successfully';
            } catch (\Exception $e) {
                Log::error('Update work experience error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $experienceCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function deleteWorkEx(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $experienceCount = ProfessionalExperience::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($experienceCount >= 1){
            try {
                $deleteItem = ProfessionalExperience::where('id', '=', $data['id'])->delete();

                $success = true;
                $get_id = $data['id'];
                $message = 'Work experience deleted successfully';
            } catch (\Exception $e) {
                Log::error('Delete work experience error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $experienceCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function addEducation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|min:2|max:250',
            'qualification' => 'required|string|min:2|max:250',
            'specialization' => 'required|string|min:2|max:250',
            'university' => 'required|string|min:2|max:250',
            'country' => 'required|integer|min:1|max:999999999999',
            'join_year' => 'required|integer|min:1900|max:2050',
            'passing_year' => 'required|integer|min:1900|max:2050',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        try {
            $education = new Qualification();
            $education->user_id = $user_id;
            $education->subject = $data['subject'];
            $education->qualification = $data['qualification'];
            $education->specialization = $data['specialization'];
            $education->university = $data['university'];
            $education->country = $data['country'];
            $education->join_year = $data['join_year'];
            $education->passing_year = $data['passing_year'];
            $education->save();

            $response = [
                'success' => true,
                'data'    => $education->id,
                'message' => 'Education added successfully!',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Add Education error: ' . $e->getMessage());
            return $this->sendError('Server error.', ['error' => $e->getMessage()]);
        }
    }

    public function updateEducation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'subject' => 'required|string|min:2|max:250',
            'qualification' => 'required|string|min:2|max:250',
            'specialization' => 'required|string|min:2|max:250',
            'university' => 'required|string|min:2|max:250',
            'country' => 'required|integer|min:1|max:999999999999',
            'join_year' => 'required|integer|min:1900|max:2050',
            'passing_year' => 'required|integer|min:1900|max:2050',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $educationCount = Qualification::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($educationCount >= 1){
            try {
                $updateItem = Qualification::where('id', '=', $data['id'])->update([
                    'subject' => $data['subject'],
                    'qualification' => $data['qualification'],
                    'specialization' => $data['specialization'],
                    'university' => $data['university'],
                    'country' => $data['country'],
                    'join_year' => $data['join_year'],
                    'passing_year' => $data['passing_year'],
                ]);

                $success = true;
                $get_id = $data['id'];
                $message = 'Education updated successfully';
            } catch (\Exception $e) {
                Log::error('Update Education error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $educationCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function deleteEducation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $educationCount = Qualification::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($educationCount >= 1){
            try {
                $deleteItem = Qualification::where('id', '=', $data['id'])->delete();

                $success = true;
                $get_id = $data['id'];
                $message = 'Education deleted successfully';
            } catch (\Exception $e) {
                Log::error('Delete Education error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $educationCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function addSkill(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'skill_id' => 'required|integer|min:1|max:999999999999',
            'level_id' => 'required|integer|min:1|max:999999999999',
            'year' => 'required|string|min:1|max:100',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        try {
            $skill = new UserSkill();
            $skill->user_id = $user_id;
            $skill->skill_id = $data['skill_id'];
            $skill->level_id = $data['level_id'];
            $skill->year = $data['year'];
            $skill->save();

            $response = [
                'success' => true,
                'data'    => $skill->id,
                'message' => 'Skill added successfully!',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Add Skill error: ' . $e->getMessage());
            return $this->sendError('Server error.', ['error' => $e->getMessage()]);
        }
    }

    public function updateSkill(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'skill_id' => 'required|integer|min:1|max:999999999999',
            'level_id' => 'required|integer|min:1|max:999999999999',
            'year' => 'required|string|min:1|max:100',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $skillCount = UserSkill::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($skillCount >= 1){
            try {
                $updateItem = UserSkill::where('id', '=', $data['id'])->update([
                    'skill_id' => $data['skill_id'],
                    'level_id' => $data['level_id'],
                    'year' => $data['year'],
                ]);

                $success = true;
                $get_id = $data['id'];
                $message = 'Skill updated successfully';
            } catch (\Exception $e) {
                Log::error('Update Skill error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $skillCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function deleteSkill(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $skillCount = UserSkill::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($skillCount >= 1){
            try {
                $deleteItem = UserSkill::where('id', '=', $data['id'])->delete();

                $success = true;
                $get_id = $data['id'];
                $message = 'Skill deleted successfully';
            } catch (\Exception $e) {
                Log::error('Delete Skill error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $skillCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function addLanguage(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'language' => 'required|string|min:1|max:250',
            'speaking' => 'required|string|min:1|max:100',
            'reading' => 'required|string|min:1|max:100',
            'writing' => 'required|string|min:1|max:100',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        try {
            $language = new JobLanguage();
            $language->user_id = $user_id;
            $language->job_id = 0; // User resume languages use 0 for job_id
            $language->language = $data['language'];
            $language->speaking = $data['speaking'];
            $language->reading = $data['reading'];
            $language->writing = $data['writing'];
            $language->save();

            $response = [
                'success' => true,
                'data'    => $language->id,
                'message' => 'Language added successfully!',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Add Language error: ' . $e->getMessage());
            Log::error('Add Language stack trace: ' . $e->getTraceAsString());
            return $this->sendError('Server error: ' . $e->getMessage(), ['error' => $e->getMessage()]);
        }
    }

    public function updateLanguage(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'language' => 'required|string|min:1|max:250',
            'speaking' => 'required|string|min:1|max:100',
            'reading' => 'required|string|min:1|max:100',
            'writing' => 'required|string|min:1|max:100',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $languageCount = JobLanguage::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($languageCount >= 1){
            try {
                $updateItem = JobLanguage::where('id', '=', $data['id'])->update([
                    'language' => $data['language'],
                    'speaking' => $data['speaking'],
                    'reading' => $data['reading'],
                    'writing' => $data['writing'],
                ]);

                $success = true;
                $get_id = $data['id'];
                $message = 'Language updated successfully';
            } catch (\Exception $e) {
                Log::error('Update Language error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $languageCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function deleteLanguage(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $languageCount = JobLanguage::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($languageCount >= 1){
            try {
                $deleteItem = JobLanguage::where('id', '=', $data['id'])->delete();

                $success = true;
                $get_id = $data['id'];
                $message = 'Language deleted successfully';
            } catch (\Exception $e) {
                Log::error('Delete Language error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $languageCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function addAppreciation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:250',
            'organization' => 'required|string|min:1|max:250',
            'month' => 'required|string|min:1|max:100',
            'year' => 'required|integer|min:1900|max:2050',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        try {
            $appreciation = new UserAppreciation();
            $appreciation->user_id = $user_id;
            $appreciation->name = $data['name'];
            $appreciation->organization = $data['organization'];
            $appreciation->month = $data['month'];
            $appreciation->year = $data['year'];
            $appreciation->save();

            $response = [
                'success' => true,
                'data'    => $appreciation->id,
                'message' => 'Appreciation added successfully!',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Add Appreciation error: ' . $e->getMessage());
            Log::error('Add Appreciation stack trace: ' . $e->getTraceAsString());
            return $this->sendError('Server error: ' . $e->getMessage(), ['error' => $e->getMessage()]);
        }
    }

    public function updateAppreciation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
            'name' => 'required|string|min:1|max:250',
            'organization' => 'required|string|min:1|max:250',
            'month' => 'required|string|min:1|max:100',
            'year' => 'required|integer|min:1900|max:2050',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $appreciationCount = UserAppreciation::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($appreciationCount >= 1){
            try {
                $updateItem = UserAppreciation::where('id', '=', $data['id'])->update([
                    'name' => $data['name'],
                    'organization' => $data['organization'],
                    'month' => $data['month'],
                    'year' => $data['year'],
                ]);

                $success = true;
                $get_id = $data['id'];
                $message = 'Appreciation updated successfully';
            } catch (\Exception $e) {
                Log::error('Update Appreciation error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $appreciationCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function deleteAppreciation(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = $request->all();
        
        $appreciationCount = UserAppreciation::where([['user_id','=',$user_id], ['id','=',$data['id']]])->get()->count();
        
        if($appreciationCount >= 1){
            try {
                $deleteItem = UserAppreciation::where('id', '=', $data['id'])->delete();

                $success = true;
                $get_id = $data['id'];
                $message = 'Appreciation deleted successfully';
            } catch (\Exception $e) {
                Log::error('Delete Appreciation error: ' . $e->getMessage());
                $success = false;
                $get_id = 0;
                $message = 'Server error: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $get_id = $appreciationCount;
            $message = 'Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data' => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }
}