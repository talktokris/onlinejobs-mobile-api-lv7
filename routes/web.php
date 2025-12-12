<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', function () {
    // Direct redirect to login page
    return redirect('/admin/login');
});

// Test route to verify app is working
Route::get('/test', function () {
    return 'Laravel is working!';
});

// Simple test route for login view
Route::get('/test-view', function () {
    try {
        return view('webapp.auth.login');
    } catch (\Exception $e) {
        return 'View Error: ' . $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

// Admin Authentication Routes
Route::prefix('admin')->namespace('WebApp')->group(function () {
    // Login routes
    Route::get('/login', 'AuthController@login')->name('admin.login');
    Route::post('/login', 'AuthController@authenticate')->name('admin.authenticate');
    Route::post('/logout', 'AuthController@logout')->name('admin.logout');
    
    // Password reset routes
    Route::get('/password/reset', 'AuthController@showForgotPassword')->name('admin.password.request');
    Route::post('/password/email', 'AuthController@sendResetLink')->name('admin.password.email');
    Route::get('/password/reset/{token}', 'AuthController@showResetForm')->name('admin.password.reset');
    Route::post('/password/reset', 'AuthController@resetPassword')->name('admin.password.update');
});

// Protected Admin Routes
Route::prefix('admin')->namespace('WebApp')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
    
    // Job Seekers
    Route::resource('job-seekers', 'JobSeekerController')->names([
        'index' => 'admin.job-seekers.index',
        'show' => 'admin.job-seekers.show',
        'edit' => 'admin.job-seekers.edit',
        'update' => 'admin.job-seekers.update',
        'destroy' => 'admin.job-seekers.destroy',
    ]);
    Route::get('/job-seekers/{jobSeekerId}/jobs/{jobId}', 'JobSeekerController@showJob')->name('admin.job-seekers.jobs.show');
    
    // Employers
    Route::resource('employers', 'EmployerController')->names([
        'index' => 'admin.employers.index',
        'show' => 'admin.employers.show',
        'edit' => 'admin.employers.edit',
        'update' => 'admin.employers.update',
        'destroy' => 'admin.employers.destroy',
    ]);
    Route::get('/employers/{employerId}/jobs/{jobId}', 'EmployerController@showJob')->name('admin.employers.jobs.show');
    Route::get('/employers/{employerId}/jobs/{jobId}/applicants/{applicantId}', 'EmployerController@showApplicant')->name('admin.employers.jobs.applicants.show');
    
    // Jobs
    Route::resource('jobs', 'JobController')->names([
        'index' => 'admin.jobs.index',
        'show' => 'admin.jobs.show',
        'edit' => 'admin.jobs.edit',
        'update' => 'admin.jobs.update',
        'destroy' => 'admin.jobs.destroy',
    ]);
    Route::get('/jobs/{jobId}/details', 'JobController@showDetails')->name('admin.jobs.details');
    Route::get('/jobs/{jobId}/applicants/{applicantId}', 'JobController@showApplicant')->name('admin.jobs.applicants.show');
    
    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', 'SettingsController@index')->name('admin.settings.index');
        Route::get('/{category}', 'SettingsController@showCategory')->name('admin.settings.category');
        Route::get('/{category}/create', 'SettingsController@createItem')->name('admin.settings.create');
        Route::post('/{category}', 'SettingsController@storeItem')->name('admin.settings.store');
        Route::get('/{category}/{id}/edit', 'SettingsController@editItem')->name('admin.settings.edit');
        Route::put('/{category}/{id}', 'SettingsController@updateItem')->name('admin.settings.update');
        Route::delete('/{category}/{id}', 'SettingsController@destroyItem')->name('admin.settings.destroy');
    });
    
    // Notifications
    Route::get('/notifications', 'NotificationController@index')->name('admin.notifications.index');
    Route::post('/notifications/blast', 'NotificationController@sendBlast')->name('admin.notifications.blast');
    Route::post('/notifications/individual', 'NotificationController@sendIndividual')->name('admin.notifications.individual');
    Route::get('/notifications/search-users', 'NotificationController@searchUsers')->name('admin.notifications.search-users');
    
    // Profile
    Route::get('/profile', 'ProfileController@show')->name('admin.profile.show');
    Route::get('/profile/edit', 'ProfileController@edit')->name('admin.profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('admin.profile.update');
    Route::get('/change-password', 'ProfileController@showChangePassword')->name('admin.change-password');
    Route::post('/change-password', 'ProfileController@changePassword')->name('admin.change-password.update');
});
