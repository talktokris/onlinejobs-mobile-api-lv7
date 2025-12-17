<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\EmployerProfile;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class EmployerProfileController extends Controller
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
        $savingPath = $this->getPublicUploadPath('assets/user_images/');

        $validator = Validator::make($request->all(), [
            'image_name' => 'required|mimes:png,jpg,gif,jpeg|max:5120', // 5MB max
            'id' => 'required|integer|min:1|max:999999999999',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try {
            $data = $request->all();
            $imageName = $data['image_name'];

            // Verify user owns this profile
            $profile = EmployerProfile::where([['user_id', '=', $user_id], ['user_id', '=', $data['id']]])->first();

            if(!$profile && $user_id != $data['id']){
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
            
            // Create user directory if it doesn't exist
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
            if($profile && $profile->company_logo && file_exists($newPath . DIRECTORY_SEPARATOR . $profile->company_logo)){
                @unlink($newPath . DIRECTORY_SEPARATOR . $profile->company_logo);
            }

            // Process and save image (400x400 as per original requirements)
            $img = Image::make($imageName);
            
            // Resize maintaining aspect ratio, fit to 400x400
            $img->fit(400, 400, function ($constraint) {
                $constraint->upsize();
            });
            
            $fullImagePath = $newPath . DIRECTORY_SEPARATOR . $getImageName;
            $upload = $img->save($fullImagePath, 85); // 85% quality

            // Verify file was actually saved
            if($upload && file_exists($fullImagePath)){
                // Set file permissions (644 = readable by all, writable by owner)
                @chmod($fullImagePath, 0644);
                
                // Update profile with new image name
                if($profile){
                    $profile->company_logo = $getImageName;
                    $profile->save();
                } else {
                    // Create profile if it doesn't exist
                    EmployerProfile::create([
                        'user_id' => $user_id,
                        'company_logo' => $getImageName,
                    ]);
                }

                // Build image URL - use asset() helper for proper URL generation
                $imageUrl = asset('assets/user_images/' . $user_id . '/' . $getImageName);

                // Log successful upload for debugging
                Log::info('Employer image uploaded successfully', [
                    'user_id' => $user_id,
                    'image_name' => $getImageName,
                    'file_path' => $fullImagePath,
                    'image_url' => $imageUrl
                ]);

                $response = [
                    'success' => true,
                    'data' => [
                        'id' => $user_id,
                        'image' => $getImageName,
                        'image_name' => $getImageName,
                        'image_url' => $imageUrl,
                    ],
                    'message' => 'Image uploaded successfully!',
                ];
                return response()->json($response, 200);
            } else {
                Log::error('Employer image save failed or file not found after save', [
                    'user_id' => $user_id,
                    'file_path' => $fullImagePath,
                    'save_result' => $upload,
                    'file_exists' => file_exists($fullImagePath)
                ]);
                return $this->sendError('Upload failed.', ['error' => 'Failed to save image. Please try again.']);
            }

        } catch (\Exception $e) {
            Log::error('Employer image upload error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Server error.', ['error' => $e->getMessage()]);
        }
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