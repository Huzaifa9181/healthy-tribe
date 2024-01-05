<?php

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

        //Users
        Route::resource('content', 'ContentController');
        Route::get('/content', 'ContentController@index')->name('content.index');
        Route::post('/content/update', 'ContentController@update')->name('content.update');
    });

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// });
// Route::view('dashboard', 'dashboard');
