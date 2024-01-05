<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\fasting_track;
use App\Models\workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\HandleResponse;

class ProgressController extends Controller
{
    //
    use HandleResponse;

    public function progress_workout ( $id = null ) {
        if($id){
            $workout = workout::select( 'id' , 'steps' , 'kcal' , 'bpm' , 'sleep' , 'created_at')->find($id);
            return $this->successWithData($workout , 'Successfully Fetch Single Progess Workout.');
        }else{
            $workout = workout::select( 'id' , 'created_at')->get();
            return $this->successWithData($workout , 'Successfully Fetch All Progess Workout.');
        }
    }

    public function progress_fasting_track ( $id = null ) {
        if($id){
            $fasting_track = fasting_track::select('id' , 'created_at' , 'updated_at')->find($id);
            $time = $this->Hourtime($fasting_track->created_at , $fasting_track->updated_at);
            return $this->successWithData($time , 'Successfully Fetch Single Progess Fasting Tracker Time.');
        }else{
            $fasting_track = fasting_track::select( 'id' , 'created_at')->get();
            return $this->successWithData($fasting_track , 'Successfully Fetch All Progess Fasting Tracker.');
        }

    }

    private function Hourtime($created_at , $updated_at){
        $timestamp1 = $created_at;
        $timestamp2 = $updated_at;

        $datetime1 = Carbon::parse($timestamp1);
        $datetime2 = Carbon::parse($timestamp2);

        // Calculate the time difference
        $timeDifference = $datetime1->diff($datetime2);

        // Format the time difference as "HH:MM:SS"
        $formattedTimeDifference = $timeDifference->format('%H:%I:%S');

        return $formattedTimeDifference;
    }

    public function progress_fasting_track_like($id){
        $fasting_track = fasting_track::find($id);
        if($fasting_track->like == null){
            $fasting_track->like = 1;
        }else{
            $fasting_track->increment('like');
        }
        $fasting_track->update();
        return $this->successWithData($fasting_track->like , 'Successfully Add Like Fasting Tracker.');
    }

    public function progress_fasting_track_comment($id){
        $user = Auth::guard('sanctum')->user();
        $comments = comment::where('user_id', $user->id)
        ->where('fasting_tracks_id', $id)
        ->get();
        return $this->successWithData($comments , 'Successfully Fetch All Comment For Fasting Track.');
    }

    public function progress_fasting_track_comment_store(Request $request){
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'fasting_tracks_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $comments = new comment;
        $comments->user_id = $user->id;
        $comments->comment = $request->comment;
        $comments->fasting_tracks_id = $request->fasting_tracks_id;
        $comments->save();
        
        return $this->successWithData($comments , 'Successfully Saved Comment For Fasting Track.');
    }

    public function progress_workout_like($id){
        $workout = workout::find($id);
        if($workout->like == null){
            $workout->like = 1;
        }else{
            $workout->increment('like');
        }
        $workout->update();
        return $this->successWithData($workout->like , 'Successfully Add Like Workout.');
    }

    public function progress_workout_comment($id){
        $user = Auth::guard('sanctum')->user();
        $comments = comment::where('user_id', $user->id)
        ->where('workout_id', $id)
        ->get();
        return $this->successWithData($comments , 'Successfully Fetch All Comment For Workout.');
    }

    public function progress_workout_comment_store(Request $request){
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'workout_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $comments = new comment;
        $comments->user_id = $user->id;
        $comments->comment = $request->comment;
        $comments->workout_id = $request->workout_id;
        $comments->save();
        
        return $this->successWithData($comments , 'Successfully Saved Comment For Workout.');
    }

}
