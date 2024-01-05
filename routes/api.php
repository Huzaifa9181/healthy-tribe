<?php

use App\Http\Controllers\Api\AuthenticateController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\fastingTrackController;
use App\Http\Controllers\Api\ProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthenticate;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware([ApiAuthenticate::class])->prefix('user')->group(function () {
    Route::controller(AuthenticateController::class)->group(function () {
        Route::post('/profileUpdate', 'profileUpdate');
        Route::post('/profile/create', 'profile');
        Route::post('/profile/update', 'profile_update');
        Route::get('/profile/notification/{status}', 'notification');
        Route::get('/profile/languages', 'languages');
        Route::post('/forgotpassword', 'forgotPassword');
        Route::post('/verifyPin', 'verifyPin');
        Route::post('/passwordChanged', 'passwordChanged');
        Route::post('/sendTextMessage', 'sendTextMessage');
    });

    Route::controller(WorkoutController::class)->group(function () {
        Route::get('/trainers', 'getTrainers');
        Route::get('/trainers/video', 'getTainerVideo');
        Route::get('/categories', 'getCategories');
        Route::get('/plans', 'getPlans');
        Route::get('/plans/{id}', 'getPlan');
        Route::get('/FetchAllPlans', 'FetchAllPlans');
        Route::get('/videos', 'getVideos');
        Route::get('/videos/{id}', 'getSpecificVideos');
        Route::post('workout/store', 'progress_workout_store');         
    });

    Route::controller(fastingTrackController::class)->group(function () {
        Route::get('/article', 'article');
        Route::post('/fasting/track_description', 'fasting_track_description');
        Route::post('/fasting/track_start', 'fasting_track_start');
        Route::post('/fasting/track_end', 'fasting_track_end');
        Route::get('/fasting/milestone', 'milestone');
        Route::get('/fasting/calender/{id?}', 'calender');
        Route::get('/challenges', 'fetchAllChallenges');
    });

    
    Route::controller(ProgressController::class)->group(function () {
        Route::get('/progress/fasting/track/{id?}', 'progress_fasting_track');       
        Route::get('/progress/fasting/like/{id}', 'progress_fasting_track_like');       
        Route::get('/progress/fasting/comments/{id}', 'progress_fasting_track_comment');       
        Route::post('/progress/fasting/comments/store', 'progress_fasting_track_comment_store');       
        // workout
        Route::get('/progress/workout/{id?}', 'progress_workout');       
        Route::get('/progress/workout/like/{id}', 'progress_workout_like');       
        Route::get('/progress/workout/comments/{id}', 'progress_workout_comment');       
        Route::post('/progress/workout/comments/store', 'progress_workout_comment_store');     

        
    });

    Route::get('/', function (Request $request) {
        return response()->json(['data' => Auth::guard('sanctum')->user()], 200);
    });
});

Route::post('/user/login', [AuthenticateController::class , 'login']);
Route::post('/user/signup', [AuthenticateController::class , 'signup']);
