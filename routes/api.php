<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProfileController;

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\EmployerController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\FieldOptionController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\DemandController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\PartnerController;

use App\Http\Controllers\AppApi\AuthAppController;
use App\Http\Controllers\AppApi\AgentAppController;
use App\Http\Controllers\AppApi\ProfileAppController;
use App\Http\Controllers\AppApi\ResumeAppController;
use App\Http\Controllers\AppApi\JobsAppController;

use App\Http\Controllers\AppApi\EmployerAppController;
use App\Http\Controllers\AppApi\EmployerAdsController;
use App\Http\Controllers\AppApi\EmployerProfileController;
use App\Http\Controllers\AppApi\EmployerResumeController;
use App\Http\Controllers\AppApi\EmployerMsgController;
use App\Http\Controllers\AppApi\UserMsgController;







Route::namespace('AppApi')->group(function(){

    // Client Auth Api Routes
    Route::post('app-user-register', 'AuthAppController@clientRegisterEmail');
    Route::post('app-user-login', 'AuthAppController@clientLoginEmail');
    Route::post('app-user-otp-login', 'AuthAppController@clientOtpLogin');
    Route::post('app-user-otp-request', 'AuthAppController@clientOtpRequest');

  
});




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    
});

Route::middleware('auth:sanctum')->group(function () {

   // Route::post('/profile-info', [ProfileController::class,'profile_'])->name('profile');

    Route::post('/user-profile-info', [ProfileAppController::class,'userProfile'])->name('postUserProfileApp');
    
    Route::post('/user-bookmarks', [ProfileAppController::class,'userBookmarks'])->name('postUserBookmarks');
    Route::post('/user-applied-jobs', [ProfileAppController::class,'userAppliedJobs'])->name('postUserAppliedJobs');
    Route::post('/user-change-password', [ProfileAppController::class,'userChangePassword'])->name('postUserChangePassword');
    Route::post('/user-support-contact', [ProfileAppController::class,'userSupportInfo'])->name('postUserSupportInfo');
    Route::post('/user-messages', [ProfileAppController::class,'userMessages'])->name('postUserBookmarks');
    Route::post('/user-messages-count', [ProfileAppController::class,'userMessageReadCount'])->name('postUserMessageReadCount');
    Route::post('/user-messages-seen', [ProfileAppController::class,'userMessageReadUpdate'])->name('postUserMessageReadupdate');
    Route::post('/user-delete-account', [ProfileAppController::class,'deleteAccount'])->name('postUserDeleteAccount');
    
    // User chat routes (job seeker)
    Route::get('/user/chat/threads', [UserMsgController::class, 'getThreads'])->name('userChatThreads');
    Route::get('/user/chat/thread/{thread_id}', [UserMsgController::class, 'getThread'])->name('userChatThread');
    Route::post('/user/chat/reply', [UserMsgController::class, 'replyMessage'])->name('userChatReply');
    Route::put('/user/chat/message/{messageId}/edit', [UserMsgController::class, 'editMessage'])->name('userChatEditMessage');
    Route::delete('/user/chat/message/{messageId}', [UserMsgController::class, 'deleteMessage'])->name('userChatDeleteMessage');
    
    Route::post('/home-top-jobs', [JobsAppController::class,'topHome'])->name('postTopHome');
    Route::post('/job-listing', [JobsAppController::class,'joblist'])->name('postJoblist');
    Route::post('/job-search', [JobsAppController::class,'jobsearch'])->name('postJobsearch');
    Route::post('/job-apply', [JobsAppController::class,'jobApply'])->name('postJobApply');
    Route::post('/revoke-job-apply', [JobsAppController::class,'revokeJobApply'])->name('postRevokeJobApply');
    Route::post('/job-bookmark', [JobsAppController::class,'jobBookmark'])->name('postJobBookmark');
    Route::post('/remove-bookmark', [JobsAppController::class,'removeBookmark'])->name('postRemoveBookmark');
    Route::post('/job-bookmark-list', [JobsAppController::class,'jobBookmarkList'])->name('postJobBookmarkList');
    Route::post('/job-applied-list', [JobsAppController::class,'JobAppliedList'])->name('postJobAppliedList');
 
    Route::post('/conatct-info-update', [ResumeAppController::class,'contactInfoUpdate'])->name('postContactInfoUpdate');
    Route::post('/personal-info-update', [ResumeAppController::class,'personalInfoUpdate'])->name('postPersonalInfoUpdate');
    Route::post('/resume-image-upload', [ResumeAppController::class,'resumeImageUpload'])->name('postResumeImageUpload');
    Route::post('/resume-image-delete', [ResumeAppController::class,'resumeImageDelete'])->name('postResumeImageDelete');

    Route::post('/resume-add-workex', [ResumeAppController::class,'addWorkEx'])->name('postAddWorkEx');
    Route::post('/resume-update-workex', [ResumeAppController::class,'editWorkEx'])->name('postEditWorkEx');
    Route::post('/resume-delete-workex', [ResumeAppController::class,'deleteWorkEx'])->name('postDeleteWorkEx');
    Route::post('/resume-add-education', [ResumeAppController::class,'addEducation'])->name('postAddEducation');
    Route::post('/resume-update-education', [ResumeAppController::class,'updateEducation'])->name('postUpdateEducation');
    Route::post('/resume-delete-education', [ResumeAppController::class,'deleteEducation'])->name('postDeleteEducation');
    Route::post('/resume-add-skill', [ResumeAppController::class,'addSkill'])->name('postAddSkill');
    Route::post('/resume-update-skill', [ResumeAppController::class,'updateSkill'])->name('postUpdateSkill');
    Route::post('/resume-delete-skill', [ResumeAppController::class,'deleteSkill'])->name('postDeleteSkill');
    Route::post('/resume-add-language', [ResumeAppController::class,'addLanguage'])->name('postAddLanguage');
    Route::post('/resume-update-language', [ResumeAppController::class,'updateLanguage'])->name('postUpdateLanguage');
    Route::post('/resume-delete-language', [ResumeAppController::class,'deleteLanguage'])->name('postDeleteLanguage');
    Route::post('/resume-add-appreciation', [ResumeAppController::class,'addAppreciation'])->name('postAddAppreciation');
    Route::post('/resume-update-appreciation', [ResumeAppController::class,'updateAppreciation'])->name('postUpdateAppreciation');
    Route::post('/resume-delete-appreciation', [ResumeAppController::class,'deleteAppreciation'])->name('postDeleteAppreciation');


    // Employer routes
    
    Route::post('/employer-home', [EmployerAppController::class,'employerHome'])->name('posteEmployerHome');
    
    Route::post('/employer-ads-listing', [EmployerAdsController::class,'employerAdsListing'])->name('postEmployerAdsListing');
    Route::post('/employer-ads-view-details', [EmployerAdsController::class,'employerAdsViewDetails'])->name('postEmployerAdsViewDetails');
    Route::post('/employer-ads-view-resume', [EmployerAdsController::class,'employerAdsViewResumeDetails'])->name('postEmployerAdsViewResumeDetails');
    Route::post('/employer-application-action', [EmployerAdsController::class,'employerApplicationAction'])->name('employerApplicationAction');
    Route::post('/employer-resumebookmarks', [EmployerResumeController::class,'employerResumeBookmarks'])->name('employerResumeBookmarks');


    Route::post('/employer-ads-create', [EmployerAdsController::class,'employerAdsCreate'])->name('postEmployerAdsCreate');
    Route::post('/employer-ads-edit', [EmployerAdsController::class,'employerAdsEdit'])->name('postEmployerAdsEdit');
    Route::post('/employer-ads-delete', [EmployerAdsController::class,'employerDdsDelete'])->name('postEmployerDdsDelete');
    Route::post('/employer-ads-description-add', [EmployerAdsController::class,'employerAdsDescriptionAdd'])->name('postEmployerAdsDescriptionAdd');
    Route::post('/employer-ads-description-edit', [EmployerAdsController::class,'employerAdsDescriptionEdit'])->name('postEmployerAdsDescriptionEdit');
    Route::post('/employer-ads-description-delete', [EmployerAdsController::class,'employerAdsDescriptionDelete'])->name('postEmployerAdsDescriptionDelete');
    
    Route::post('/employer-ads-requirement-add', [EmployerAdsController::class,'employerAdsRequirementAdd'])->name('postEmployerAdsEequirementAdd');
    Route::post('/employer-ads-requirement-edit', [EmployerAdsController::class,'employerAdsRequirementEdit'])->name('postEmployerAdsRequirementEdit');
    Route::post('/employer-ads-requirement-delete', [EmployerAdsController::class,'employerAdsRequirementDelete'])->name('postEmployerAdsRequirementDelete');
    
    
    
    Route::post('/employer-resume-search', [EmployerResumeController::class,'employerResumeSearch'])->name('postEmployerResumeSearch');
    Route::get('/employer-resume-popular-keywords', [EmployerResumeController::class,'getPopularSearchKeywords'])->name('getPopularSearchKeywords');
    Route::post('/employer-resume-view', [EmployerResumeController::class,'employerResumeView'])->name('postEmployerResumeView');
    Route::post('/employer-resume-contact-view', [EmployerResumeController::class,'employerResumeContactView'])->name('postEmployerResumeContactView');
    Route::post('/employer-resume-select-action', [EmployerResumeController::class,'employerResumeSelectAction'])->name('postEmployerResumeSelectAction');


    Route::post('/employer-messages', [EmployerMsgController::class,'employerMessages'])->name('employerMessages');
    Route::post('/employer-messages-count', [EmployerMsgController::class,'employerMessageReadCount'])->name('employerMessageReadCount');
    Route::post('/employer-messages-seen', [EmployerMsgController::class,'employerMessageReadUpdate'])->name('employerMessageReadUpdate');
    
    Route::post('/employer-message-thread-listing', [EmployerMsgController::class,'employerMessageThreadListing'])->name('postEmployerMessageThreadListing');
    Route::post('/employer-message-thread-view', [EmployerMsgController::class,'employerMessageThreadView'])->name('postEmployerMessageThreadView');
    Route::post('/employer-message-thread-create', [EmployerMsgController::class,'employerMessageThreadCreate'])->name('postEmployerMessageThreadCreate');
    Route::post('/employer-message-thread-reply', [EmployerMsgController::class,'employerMessageThreadReply'])->name('postEmployerMessageThreadReply');
    
    // New chat routes
    Route::post('/employer/chat/start', [EmployerMsgController::class, 'startChat'])->name('employerChatStart');
    Route::get('/employer/chat/threads', [EmployerMsgController::class, 'getThreads'])->name('employerChatThreads');
    Route::get('/employer/chat/thread/{thread_id}', [EmployerMsgController::class, 'getThread'])->name('employerChatThread');
    Route::post('/employer/chat/send', [EmployerMsgController::class, 'sendMessage'])->name('employerChatSend');
    Route::post('/employer/chat/edit', [EmployerMsgController::class, 'editMessage'])->name('employerChatEdit');
    Route::post('/employer/chat/delete', [EmployerMsgController::class, 'deleteMessage'])->name('employerChatDelete');
    Route::post('/employer/chat/edit', [EmployerMsgController::class, 'editMessage'])->name('employerChatEdit');
    Route::post('/employer/chat/delete', [EmployerMsgController::class, 'deleteMessage'])->name('employerChatDelete');
  

        
    Route::post('/employer-account-profile-update', [EmployerProfileController::class,'employerAccountProfileUpdate'])->name('postEmployerAccountProfileUpdate');
    Route::post('/employer-account-image-upload', [EmployerProfileController::class,'employerAccountImageUpload'])->name('postEmployerAccountProfileUpdate');
   
    
    Route::post('/employer-account-resume-shortlisted', [EmployerProfileController::class,'employerAccountResumeShortlisted'])->name('postEmployerAccountResumeShortlisted');
    Route::post('/employer-account-resume-hired', [EmployerProfileController::class,'employerAccountResumeHired'])->name('postEmployerAccountResumeHired');
    Route::post('/employer-account-support', [EmployerProfileController::class,'employerAccountSupport'])->name('postEmployerAccountSupport');

   // Vender Api Routes

    // Route::post('/vender-menu-heading-store', [FoodMenuController::class,'headingStore'])->name('vender-menu-heading-store');
    // Route::post('/vender-menu-heading-edit', [FoodMenuController::class,'headingEdit'])->name('vender-menu-heading-edit');
    // Route::post('/vender-menu-heading-delete', [FoodMenuController::class,'headingDelete'])->name('vender-menu-heading-delete');

    

   // Route::get('/staus-message', [StatusFillsController::class, 'status']);
   // Route::get('/users-role', [StatusFillsController::class, 'userRole']);
});


// Old System Routes

Route::get('/countries', [CountryController::class,'index'])->name('countries');

Route::get('/states', [StateController::class, 'index'])->name('states');

Route::get('/cities', [CityController::class, 'index'])->name('cities');

Route::get('/positions', [PositionController::class, 'index'])->name('positions');

Route::get('/field-options', [FieldOptionController::class, 'index'])->name('field-options');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs');

Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs-view'); //completed

Route::get('/companies', [EmployerController::class, 'companies'])->name('companies');

Route::get('/companies/{id}', [EmployerController::class, 'companyDetail'])->name('companies-details');  //Completed

Route::get('/companies/{id}/jobs', [EmployerController::class, 'companyJobs'])->name('companies-jobs-list');//to do  company le post gareko job list 

Route::post('/signup', [RegisterController::class, 'register'])->name('signup');

Route::post('/seeker-signup', [RegisterController::class, 'seekerSignUp'])->name('seeker-signup');

Route::post('/employer-signup', [RegisterController::class, 'employerSignUp'])->name('employer-signup');

Route::post('/partner-signup', [RegisterController::class, 'partnerSignUp'])->name('partner-signup');

Route::post('/verify-email', [UserController::class, 'verifyEmail'])->name('verify-email');

Route::post('/request-otp', [UserController::class, 'requestOtp'])->name('request-otp');

Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password');



Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('/country',[CountryController::class,'index']);

    // Route::get('/partner/foreign-worker/{id}',function(){
    //     return id;
    // });

    Route::get('/profile', [UserController::class, 'getProfile']);

    Route::get('/demand/{id}', [EmployerController::class, 'show']);
    Route::post('/logout', [UserController::class, 'logout']);


    // update seeker
    Route::post('/seeker/profile', [UserController::class, 'updateSeekerProfile']);


    // update partner
    Route::post('/partner/profile', [UserController::class, 'updatePartnerProfile']);

    // update Employer
    Route::post('/employer/profile', [UserController::class, 'updateEmployerProfile']);




    Route::group(["prefix" => "partner/"], function () {
        Route::get('worker-demands', [DemandController::class, 'getEmployersDemandDataForAgent']);
        Route::get('worker-demands/{id}', [DemandController::class, 'getEmployersDemandDetailsForAgent']);
        Route::get('worker-demands/{id}/workers', [DemandController::class, 'getEmployersDemandWorkersForAgent']);

        Route::get('foreign-workers', [WorkerController::class, 'getAgentForeignWorkers']);
        Route::get('foreign-workers/{id}', [WorkerController::class, 'ForeignWorkerDetail']);
        Route::post('foreign-workers', [WorkerController::class, 'ForeignWorkerDetail']);     //to do

        Route::post('add-foreign-worker', [WorkerController::class, 'addForeignWorker']);

        Route::patch('/{id}', [PartnerController::class, 'updateProfile']);
    });

    Route::group(["prefix" => "employer/"], function () {
        Route::get('demands', [DemandController::class, 'getEmployersDemandData']);       //completed
        Route::get('demands/{id}', [DemandController::class, 'getEmployersDemandDetails']);       //completed
        Route::get('demands/{id}/workers', [DemandController::class, 'getEmployersDemandWorkers']);       //completed
        Route::post('demands', [DemandController::class, 'getEmployersDemandData']);  //to do
        Route::get('jobs', [JobController::class, 'getEmployersJobs']);      // completed
        Route::post('save-job', [JobController::class, 'saveJob']);
        Route::post('save-demand', [DemandController::class, 'saveDemand']);
        Route::patch('/{id}', [EmployerController::class, 'updateProfile']);
    });



    Route::post('/file-upload', [UserController::class, 'fileUpload']);
});

Route::post("login", [UserController::class, 'index']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});