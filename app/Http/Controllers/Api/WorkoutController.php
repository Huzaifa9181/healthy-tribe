<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\categorie;
use App\Models\plan;
use App\Models\User;
use App\Models\video;
use App\Models\workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    //
    public function getTrainers(){
        // Retrieve a unique collection of user IDs from the plans
        $uniquePlanUserIds = Plan::pluck('user_id')->unique();

        // Retrieve the last 10 trainers who have at least one plan
        $trainers = User::whereIn('id', $uniquePlanUserIds)  // Filter users who are associated with at least one plan
                    ->where('role_id', 2)  // Filter users who are trainers
                    ->latest()  // Ordering by the latest first
                    ->limit(10)  // Limiting the results to 10
                    ->get();  // Getting the collective

        return response()->json(['data' => $trainers],200);
    }

    public function getCategories(){
        $categorie = categorie::all();
        return response()->json(['data' => $categorie],200);
    }

    public function getPlans(){
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if($lastWorkout) {
            // Retrieve the next plan based on the plan_id from the last workout
            $nextPlan = Plan::where('user_id', $lastWorkout->user_id)
                            ->where('id', '>', $lastWorkout->plan_id)
                            ->orderBy('id', 'asc') // Assuming plans are ordered by ID
                            ->first(); // Get the next record
        } else {
            $nextPlan = plan::where('user_id' , $user->favourite_trainer)->first();
        }
        return response()->json(['data' => $nextPlan],200);
    }

    public function getVideos(){
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if($lastWorkout) {
            // Retrieve the next plan based on the plan_id from the last workout
            $nextPlan = Plan::where('user_id', $lastWorkout->user_id)
                            ->where('id', '>', $lastWorkout->plan_id)
                            ->orderBy('id', 'asc') // Assuming plans are ordered by ID
                            ->first(); // Get the next record
        } else {
            $nextPlan = plan::where('user_id' , $user->favourite_trainer)->first();
        }

        if($nextPlan){
            $video = video::where('plan_id' , $nextPlan->id)
            ->limit(10)  // Limiting the results to 10
            ->get();
        }else{
            $video = video::latest()
            ->limit(10)  // Limiting the results to 10
            ->get();
        }
        return response()->json(['data' => $video],200);
    }

    public function getPlan($id){
        $plan = Plan::find($id);
        $video = $plan->videos;
        return response()->json(['data' => $plan , 'video' => $video],200);

    }
}
