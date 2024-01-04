<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\article;
use App\Models\challenge;
use App\Models\fasting_track;
use App\Models\meal;
use Illuminate\Http\Request;
use App\Traits\HandleResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class fastingTrackController extends Controller
{
    //
    use HandleResponse;

    public function article (){
        $latestArticle = article::latest()->first();
        return $this->successWithData($latestArticle , 'Successfully Fetch Latest Article.');
    }

    public function fasting_track_description (Request $request) {
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $fasting_track = new fasting_track;
        $fasting_track->description = $request->description;
        $fasting_track->user_id = $user->user_id;
        $fasting_track->save();

        return $this->successMessage('Fasting Track Note Saved.');
    }

    public function fasting_track_start (Request $request) {
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'start_time' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        
        $fasting_track = fasting_track::where('user_id' , $user->id )->latest()->first();
        $fasting_track->start_time = $request->start_time ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Time Started.');
    }

    public function fasting_track_end (Request $request) {
        $user = Auth::guard('sanctum')->user();
        $meal = new Meal;
        if ($request->file('image')) {
            $image = $request->file('image');
            
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/meal_images'), $filename);

            // Set the relative image path in the meal model
            $meal->image = 'assets/meal_images/' . $filename;
        }

        $meal->type = $request->meal_note ?? '';
        $meal->quantity = $request->quantity ?? '';
        $meal->meal_composition_id = $request->meal_composition_id ?? '';
        $meal->save();


        $fasting_track = fasting_track::where( 'user_id' ,$user->id)->latest()->first();
        $fasting_track->end_time = $request->end_time ?? '';
        $fasting_track->weight = $request->weight ?? '';
        $fasting_track->activity_type = $request->activity_type ?? '';
        $fasting_track->feeling = $request->feeling ?? '';
        $fasting_track->meal_id = $meal->id ?? '';
        $fasting_track->note = $request->feeling_note ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Note Saved.');
    }

    public function fetchAllChallenges(){
        $challenge = challenge::all();
        return $this->successWithData($challenge , 'Successfully Fetch All Challenges.');
    }
}
