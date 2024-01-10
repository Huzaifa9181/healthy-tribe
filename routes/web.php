<?php

use App\Http\Controllers\Admin\AchieveController;
use App\Http\Controllers\Admin\AddictionController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\WorkoutCatController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\MotivationController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', url('/admin/login'));

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::get('login', 'AuthController@showLoginForm')->name('admin.login');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout')->name('admin.logout');
});

// Protected Admin Routes
Route::prefix('admin')->namespace('App\Http\Controllers\Admin')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/home', 'HomeController@index');

        // Admin
        Route::resource('admins', 'AdminController');
        Route::get('/admins/{id}/edit', 'AdminController@edit')->name('admin.edit');
        Route::post('/admins/update/{id}', 'AdminController@update')->name('admin.update');
        Route::get('/getAdmin', 'AdminController@getAdmin')->name('admin.getAdmin');
        Route::get('/admins', 'AdminController@index')->name('admin.index');

        //Users
        Route::resource('users', 'UserController');
        Route::get('/getUsers', 'UserController@getUsers')->name('users.getUsers');
        Route::get('/users', 'UserController@index')->name('users.index');
        Route::get('/users/create', 'UserController@create')->name('users.create');
        Route::get('/users/edit/{id}', 'UserController@edit')->name('users.edit');
        Route::post('/users/store', 'UserController@store')->name('users.store');
        Route::post('/users/update', 'UserController@update')->name('users.update');
        Route::post('/users/destroy', 'UserController@destroy')->name('users.destroy');

        //Subscription
        Route::resource('subscription', 'SubscriptionController');
        Route::get('/getSubscription', 'SubscriptionController@getSubscription')->name('subscription.getSubscription');
        Route::get('/subscription', 'SubscriptionController@index')->name('subscription.index');
        Route::get('/subscription/create', 'SubscriptionController@create')->name('subscription.create');
        Route::get('/subscription/edit/{id}', 'SubscriptionController@edit')->name('subscription.edit');
        Route::post('/subscription/store', 'SubscriptionController@store')->name('subscription.store');
        Route::post('/subscription/update', 'SubscriptionController@update')->name('subscription.update');
        Route::post('/subscription/destroy', 'SubscriptionController@destroy')->name('subscription.destroy');

        //App Content
        Route::resource('content', 'ContentController');
        Route::get('/content', 'ContentController@index')->name('content.index');
        Route::post('/content/update', 'ContentController@update')->name('content.update');

        //Workout Categories
        Route::resource('workout_cat', 'WorkoutCatController');
        Route::get('/get_workout_cat', 'WorkoutCatController@get_workout_cat')->name('workout_cat.get_workout_cat');
        Route::get('/workout_cat', 'WorkoutCatController@index')->name('workout_cat.index');
        Route::get('/workout_cat/create', 'WorkoutCatController@create')->name('workout_cat.create');
        Route::get('/workout_cat/edit/{id}', 'WorkoutCatController@edit')->name('workout_cat.edit');
        Route::post('/workout_cat/store', 'WorkoutCatController@store')->name('workout_cat.store');
        Route::post('/workout_cat/update', 'WorkoutCatController@update')->name('workout_cat.update');
        Route::post('/workout_cat/destroy', 'WorkoutCatController@destroy')->name('workout_cat.destroy');
        Route::controller(ChatController::class)->group(function () {
            Route::get('/chat', 'index');
        });

        Route::controller(WorkoutCatController::class)->group(function () {
            Route::get('/trainer/video', 'trainer_video_index')->name('trainer_video.index');
            Route::get('/trainer/video/getVideo', 'trainer_getVideo')->name('trainer_video.show');
            Route::get('/trainer/video/create', 'trainer_video_create')->name('trainer_video.create');
            Route::post('/trainer/video/store', 'trainer_video_store')->name('trainer_video.store');
            Route::post('/trainer/video/update', 'trainer_video_update')->name('trainer_video.update');
            Route::get('/trainer/video/edit/{id}', 'trainer_editVideo')->name('trainer_video.edit');
            Route::post('/videos/destroy', 'video_destroy')->name('trainer_video.destroy');
        });

        Route::controller(ChallengeController::class)->group(function () {
            Route::get('/challenge', 'index')->name('challenge.index');
            Route::get('/challenge/getData', 'getData')->name('challenge.show');
            Route::get('/challenge/create', 'create')->name('challenge.create');
            Route::post('/challenge/store', 'store')->name('challenge.store');
            Route::post('/challenge/update', 'update')->name('challenge.update');
            Route::get('/challenge/edit/{id}', 'edit')->name('challenge.edit');
            Route::post('/challenges/destroy', 'destroy')->name('trainer_video.destroy');
        });

        Route::controller(QuestionController::class)->group(function () {
            Route::get('/question', 'index')->name('question.index');
            Route::get('/question/getData', 'getData')->name('question.show');
            Route::get('/question/create', 'create')->name('question.create');
            Route::post('/question/store', 'store')->name('question.store');
            Route::post('/question/update', 'update')->name('question.update');
            Route::get('/question/edit/{id}', 'edit')->name('question.edit');
            Route::post('/question/destroy', 'destroy')->name('question.destroy');
        });

        Route::controller(AddictionController::class)->group(function () {
            Route::get('/addiction', 'index')->name('addiction.index');
            Route::get('/addiction/getData', 'getData')->name('addiction.show');
            Route::get('/addiction/create', 'create')->name('addiction.create');
            Route::post('/addiction/store', 'store')->name('addiction.store');
            Route::post('/addiction/update', 'update')->name('addiction.update');
            Route::get('/addiction/edit/{id}', 'edit')->name('addiction.edit');
            Route::post('/addiction/destroy', 'destroy')->name('addiction.destroy');
        });

        Route::controller(ArticleController::class)->group(function () {
            Route::get('/article', 'index')->name('article.index');
            Route::get('/article/getData', 'getData')->name('article.show');
            Route::get('/article/create', 'create')->name('article.create');
            Route::post('/article/store', 'store')->name('article.store');
            Route::post('/article/update', 'update')->name('article.update');
            Route::get('/article/edit/{id}', 'edit')->name('article.edit');
            Route::post('/articles/destroy', 'destroy')->name('article.destroy');
        });

        Route::controller(GroupController::class)->group(function () {
            Route::get('/group', 'index')->name('group.index');
            Route::get('/group/getData', 'getData')->name('group.show');
            Route::get('/group/create', 'create')->name('group.create');
            Route::post('/group/store', 'store')->name('group.store');
            Route::post('/group/update', 'update')->name('group.update');
            Route::get('/group/edit/{id}', 'edit')->name('group.edit');
            Route::post('/group/destroy', 'destroy')->name('group.destroy');
        });

        Route::controller(MotivationController::class)->group(function () {
            Route::get('/motivation', 'index')->name('motivation.index');
            Route::get('/motivation/getData', 'getData')->name('motivation.show');
            Route::get('/motivation/create', 'create')->name('motivation.create');
            Route::post('/motivation/store', 'store')->name('motivation.store');
            Route::post('/motivation/update', 'update')->name('motivation.update');
            Route::get('/motivation/edit/{id}', 'edit')->name('motivation.edit');
            Route::post('/motivation/destroy', 'destroy')->name('motivation.destroy');
        });


        Route::controller(CurrencyController::class)->group(function () {
            Route::get('/currency', 'index')->name('currency.index');
            Route::get('/currency/getData', 'getData')->name('currency.show');
            Route::get('/currency/create', 'create')->name('currency.create');
            Route::post('/currency/store', 'store')->name('currency.store');
            Route::post('/currency/update', 'update')->name('currency.update');
            Route::get('/currency/edit/{id}', 'edit')->name('currency.edit');
            Route::post('/currency/destroy', 'destroy')->name('currency.destroy');
        });

        Route::controller(AchieveController::class)->group(function () {
            Route::get('/achieve', 'index')->name('achieve.index');
            Route::get('/achieve/getData', 'getData')->name('achieve.show');
            Route::get('/achieve/create', 'create')->name('achieve.create');
            Route::post('/achieve/store', 'store')->name('achieve.store');
            Route::post('/achieve/update', 'update')->name('achieve.update');
            Route::get('/achieve/edit/{id}', 'edit')->name('achieve.edit');
            Route::post('/achieve/destroy', 'destroy')->name('achieve.destroy');
        });

        Route::controller(PlanController::class)->group(function () {
            Route::get('/plan', 'plan_index')->name('plan.index');
            Route::get('/plan/getPlans', 'getPlans')->name('plan.show');
            Route::get('/plan/create', 'plan_create')->name('plan.create');
            Route::post('/plan/store', 'plan_store')->name('plan.store');
            Route::get('/plan/edit/{id}', 'plan_edit')->name('plan.edit');
            Route::post('/plan/update', 'plan_update')->name('plan.update');
            Route::post('/plans/destroy', 'destroy')->name('plan.destroy');
            // video Upload
            Route::get('/plan/video', 'plan_video_index')->name('plan_video.index');
            Route::get('/plan/video/getPlans', 'getPlansVideo')->name('plan_video.show');
            Route::get('/plan/video/create', 'plan_video_create')->name('plan_video.create');
            Route::post('/plan/video/store', 'plan_video_store')->name('plan_video.store');
            Route::get('/plan/video/edit/{id}', 'plan_video_edit')->name('plan_video.edit');
            Route::post('/plan/video/update', 'plan_video_update')->name('plan_video.update');
            // Route::post('/videos/destroy', 'destroy')->name('plan.destroy');
        });

    });
