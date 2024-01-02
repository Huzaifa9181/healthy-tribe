<?php

use App\Http\Controllers\Api\AuthenticateController;
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
    Route::post('/profile/create', [AuthenticateController::class , 'profile']);    
    Route::post('/profile/update', [AuthenticateController::class , 'profile_update']);    
    Route::get('/profile/notification/{status}', [AuthenticateController::class , 'notification']);    
    Route::get('/profile/languages', [AuthenticateController::class , 'languages']);    
    Route::post('/forgotpassword', [AuthenticateController::class , 'forgotPassword']);    
    Route::post('/verifyPin', [AuthenticateController::class , 'verifyPin']);    
    Route::post('/passwordChanged', [AuthenticateController::class , 'passwordChanged']);    
    Route::get('/', function (Request $request) {
        return response()->json(['data' => Auth::guard('sanctum')->user()], 200);
    });
});

Route::post('/user/login', [AuthenticateController::class , 'login']);
Route::post('/user/signup', [AuthenticateController::class , 'signup']);
