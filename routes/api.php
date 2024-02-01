<?php

use App\Http\Controllers\Api\AddictionController;
use App\Http\Controllers\Api\AuthenticateController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\fastingTrackController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\SocialController;
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

Route::controller(AuthenticateController::class)->prefix('user')->group(function () {
    Route::post('/forgotpassword', 'forgotPassword');
    Route::post('/verifyPin', 'verifyPin');
    Route::post('/passwordChanged', 'passwordChanged');
    Route::post('/sendTextMessage', 'sendTextMessage');
});



Route::middleware([ApiAuthenticate::class])->prefix('user')->group(function () {
    Route::controller(AuthenticateController::class)->group(function () {
        Route::post('/profileUpdate', 'profileUpdate');
        Route::post('/profile/create', 'profile');
        Route::post('/profile/update', 'profile_update');
        Route::get('/profile/notification/{status}', 'notification');
        Route::get('/profile/languages', 'languages');
        Route::get('/profile/content', 'content');
    });

    Route::controller(WorkoutController::class)->group(function () {
        Route::get('/trainers', 'getTrainers');
        Route::get('/trainers/video', 'getTainerVideo');
        Route::get('/categories/{id?}', 'getCategories');
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
        Route::post('/fasting/end/store', 'fasting_end_store');
        Route::post('/fasting/activity/store', 'fasting_activity_store');
        Route::post('/fasting/Weight/store', 'fasting_weight_store');
        Route::post('/fasting/meal/store', 'fasting_meal_store');
        Route::post('/fasting/mood/store', 'fasting_mood_store');
        Route::post('/fasting/achieve/store', 'fasting_achieve_store');
        Route::get('/fasting/calender/{id?}', 'calender');
        Route::get('/challenges', 'fetchAllChallenges');
        Route::get('/achieve/list', 'fetchAllachieve');
    });

    
    Route::controller(ProgressController::class)->group(function () {
        Route::get('/progress/fasting/track/{id?}', 'progress_fasting_track');       
        Route::get('/progress/fasting/like/{id}', 'progress_fasting_track_like');       
        Route::get('/progress/fasting/unlike/{id}', 'progress_fasting_track_unlike');       
        Route::get('/progress/fasting/comments/{id}', 'progress_fasting_track_comment');       
        Route::post('/progress/fasting/comments/store', 'progress_fasting_track_comment_store');       
        // workout
        Route::get('/progress/workout/{id?}', 'progress_workout');       
        Route::get('/progress/workout/like/{id}', 'progress_workout_like');       
        Route::get('/progress/workout/unlike/{id}', 'progress_workout_unlike');       
        Route::get('/progress/workout/comments/{id}', 'progress_workout_comment');       
        Route::post('/progress/workout/comments/store', 'progress_workout_comment_store');     
        Route::get('/progress/calender', 'ProgressCalender');     
        Route::get('/progress/calender/like/{id?}', 'ProgressCalendeLike');     
        Route::get('/progress/calender/unlike/{id?}', 'ProgressCalendeUnlike');     
        
    });

    Route::controller(PostController::class)->group(function () {
        Route::post('/post/store', 'post_store');     
        Route::get('/post/like/{id}', 'post_like');     
        Route::get('/post/unlike/{id}', 'post_unlike');     
        Route::get('/post/comments/{id}', 'post_comment');       
        Route::post('/post/comments/store', 'post_comment_store');     
        Route::post('/story/store', 'story_store');     
        Route::get('/story/fetch/{id?}', 'story_fetch');     
        Route::get('/story/{id?}/{status?}', 'story_like');     
    });

    Route::controller(SocialController::class)->group(function () {
        Route::get('/fetchChat/{id?}', 'fetchChat');
        Route::post('/sendMessage', 'sendMessage');
        Route::post('/chat/block', 'chat_block');
        Route::post('/chat/delete', 'chat_delete');
        Route::post('/group/MemberAdded', 'addMember');
        Route::get('/group/FetchAllGroup', 'FetchAllGroup');
        Route::post('/group/SendGroupMessage', 'SendGroupMessage');
        Route::get('/group/fetchChat/{id?}', 'group_fetchChat');
        Route::post('/Agent/sendMessage', 'AgentsendMessage');
        Route::post('/Agent/sendMessage', 'AgentsendMessage');
        Route::get('/achievement', 'getAchievement');
        Route::post('/achievement/store', 'achievement_store');
    });

    Route::controller(AddictionController::class)->group(function () {
        Route::get('addiction/type/{id?}' , 'addiction_type');
        Route::get('addiction/question/{id}' , 'addiction_question');
        Route::post('addiction/question/store' , 'addiction_question_store');
        Route::get('currency/list' , 'currency_list');
        Route::get('motivation/list' , 'motivate_list');
        Route::get('resource/training/{id}' , 'resource_training');
        Route::post('addiction/money/store' , 'addiction_money_store');
        Route::post('addiction/time/store' , 'addiction_time_store');
        Route::get('addiction/track/{type}' , 'addiction_track');
        Route::get('addiction/reset' , 'addiction_reset');
        Route::get('/addiction/management/calender/{id}', 'progress_addiction_calender');
        Route::post('/addiction/management/store', 'addiction_management_store');
        Route::get('/addiction/management/like/{id?}', 'AddictionManagementLikeCalender');    
        Route::get('/addiction/management/unlike/{id?}', 'AddictionManagementUnlikeCalender');    
        Route::get('/addiction/management/comment/{id?}', 'progress_addiction_comment');    
        Route::post('/addiction/management/comment/store', 'progress_addiction_comment_store');    
    });

    Route::controller(CommunityController::class)->group(function () { 
        Route::get('community/list/{addiction_id?}' , 'comunity_list');
        Route::post('community/store' , 'community_store');
        Route::post('community/comment/store' , 'community_comment_store');
        Route::post('reply/store' , 'reply_store');
        Route::post('InnerReply/store' , 'inner_reply_store');
        Route::get('InnerReply/like/{id?}' , 'InnerReply_like');
        Route::get('InnerReply/unlike/{id?}' , 'InnerReply_unlike');
        Route::get('comment/like/{id?}' , 'comment_like');
        Route::get('comment/unlike/{id?}' , 'comment_unlike');
        Route::get('reply/like/{id?}' , 'reply_like');
        Route::get('reply/unlike/{id?}' , 'reply_unlike');
    });


    Route::get('/', function (Request $request) {
        return response()->json(['data' => Auth::guard('sanctum')->user()], 200);
    });
});

Route::post('/user/login', [AuthenticateController::class , 'login']);
Route::post('/user/signup', [AuthenticateController::class , 'signup']);
