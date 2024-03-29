<?php

namespace App\Http\Controllers\Api;

use App\Events\chatMessage;
use App\Http\Controllers\Controller;
use App\Models\achieve;
use App\Models\article;
use App\Models\challenge;
use App\Models\fasting_track;
use App\Models\meal;
use App\Models\message;
use Illuminate\Http\Request;
use App\Traits\HandleResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class fastingTrackController extends Controller
{
    //
    use HandleResponse;

    public function article (){
        $latestArticle = article::latest()->first();
        return $this->successWithData($latestArticle , 'Successfully Fetch Latest Article.');
    }

    public function fasting_track_start (Request $request) {
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'start_time' => 'required',
            'description' => 'required',
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        
        $fasting_track = new fasting_track;
        $fasting_track->start_time = $request->start_time ?? '';
        $fasting_track->description = $request->description;
        $fasting_track->user_id = $request->user_id;
        $fasting_track->save();

        return $this->successMessage('Fasting Track Time Started.');
    }

    public function fasting_end_store (Request $request) {
        $user = Auth::guard('sanctum')->user();

        $fasting_track = fasting_track::where( 'user_id' ,$user->id)->latest()->first();
        $fasting_track->end_time = $request->end_time ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track End Saved.');
    }

    public function fasting_activity_store (Request $request) {
        $user = Auth::guard('sanctum')->user();

        $fasting_track = fasting_track::where( 'user_id' ,$user->id)->latest()->first();
        $fasting_track->end_time = $request->end_time ?? '';
        
        $fasting_track->activity_type = $request->activity_type ?? '';
        $fasting_track->started_activity = $request->started_activity ?? '';
        $fasting_track->finished_activity = $request->finished_activity ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Activity Saved.');
    }

    public function fasting_weight_store(Request $request) {
        $user = Auth::guard('sanctum')->user();

        $fasting_track = fasting_track::where( 'user_id' ,$user->id)->latest()->first();
        
        $fasting_track->weight = $request->weight ?? '';
        $fasting_track->weight_time = $request->weight_time ?? '';
        $fasting_track->weight_date = $request->weight_date ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Weight Saved.');
    }


    public function fasting_mood_store (Request $request) {
        $user = Auth::guard('sanctum')->user();

        $fasting_track = fasting_track::where( 'user_id' ,$user->id)->latest()->first();
        $fasting_track->feeling = $request->feeling ?? '';
        $fasting_track->note = $request->feeling_note ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Mood Saved.');
    }


    public function fasting_meal_store (Request $request) {
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
        $fasting_track->meal_id = $meal->id ?? '';
        $fasting_track->update();

        return $this->successMessage('Fasting Track Meal Saved.');
    }

    public function fetchAllChallenges(){
        $challenges = Challenge::all()->map(function ($challenge) {
            if ($challenge->image) {
                // Modify the image attribute to be a full URL
                $challenge->image = URL::to('/') . '/public/' . $challenge->image;
            }
            return $challenge;
        });
        
        return $this->successWithData($challenges , 'Successfully Fetch All Challenges.');
    }

    public function milestone(){
        $user = Auth::guard('sanctum')->user();
        $count = fasting_track::where( 'user_id' ,$user->id)->count();
        return $this->successWithData($count , 'Successfully Get Milestone.');
    }

    public function calender($id = null){
        $user = Auth::guard('sanctum')->user();
        if($id){
            $records = fasting_track::select('start_time', 'end_time', 'created_at')
            ->find($id);
            $dateString = $records->created_at;
            $date = Carbon::parse($dateString);

            $dayName = $date->format('l'); // 'l' format gives the full day name
            $dateString2 = $records->updated_at;
            $date2 = Carbon::parse($dateString2);

            $dayName2 = $date2->format('l'); // 'l' format gives the full day name

            $start =  $dayName.', ' .$records->start_time ?? '';
            $end =  $dayName2.', ' .$records->end_time ?? '';
            $response['started_fasting'] = $start;
            $response['goal_reached'] = $end;
            // $records->start_time;
            // $records->end_time;
        }else{
            $records = fasting_track::select('start_time', 'end_time', 'created_at')
                ->where('user_id', $user->id)
                ->get();
            
            $response = [];
            
            foreach ($records as $val) {
                $recordData = [
                    'hours' => $this->getHourDifference($val->start_time, $val->end_time),
                    'date' => $val->created_at,
                ];
                $response[] = $recordData;
            }
        }
        
        return $this->successWithData($response , 'Successfully Fetch Calender Details.');

    }

    private function getHourDifference($intal_time , $final_time){
        $start_time = Carbon::parse($intal_time);
        $end_time = Carbon::parse($final_time);

        // Calculate the difference in hours
        return $hours_difference = $start_time->diffInHours($end_time);
    }

    public function fetchAllachieve (){
        $latestArticle = achieve::all();
        return $this->successWithData($latestArticle , 'Successfully Fetch Achieve.');
    }
    
}
