<?php

use App\Http\Controllers\Admin\WorkoutCatController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\PlanController;
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

        Route::controller(PlanController::class)->group(function () {
            Route::get('/plan', 'plan_index')->name('plan.index');
            Route::get('/plan/getPlans', 'getPlans')->name('plan.show');
            Route::get('/plan/create', 'plan_create')->name('plan.create');
            Route::post('/plan/store', 'plan_store')->name('plan.store');
            Route::get('/plan/edit/{id}', 'plan_edit')->name('plan.edit');
            Route::post('/plan/update', 'plan_update')->name('plan.update');
            Route::post('/plan/destroy', 'plan_destroy')->name('plan.destroy');
        });

    });
