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
use App\Traits\HandleResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class WorkoutController extends Controller
{
    //
    use HandleResponse;

    public function getTrainers(){
        // Retrieve a unique collection of user IDs from the plans
        $uniquePlanUserIds = Plan::pluck('user_id')->unique();

        // Retrieve the last 10 trainers who have at least one plan
        $trainers = User::whereIn('id', $uniquePlanUserIds)  // Filter users who are associated with at least one plan
                    ->where('role_id', 2)  // Filter users who are trainers
                    ->latest()  // Ordering by the latest first
                    ->limit(10)  // Limiting the results to 10
                    ->get();  // Getting the collective

        return $this->successWithData($trainers , 'Successfully Fetch 10 Trainers');
    }

    public function getCategories(){
        $categorie = categorie::latest()  // Ordering by the latest first
        ->limit(10)  // Limiting the results to 10
        ->get();  // Getting the collective;
        return $this->successWithData($categorie , 'Successfully Fetch 10 Categories');
    }

    public function FetchAllPlans(){
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();
        $plan = plan::find($lastWorkout->plan_id);
        if($plan) {
            $plans = plan::where('user_id' , $plan->user_id)->get();
            $data = [];
            foreach($plans as $val){
                $val->locked = $this->checkLocked($val->id,$user->id);
                $data[] = $val;
            }
            
        } 

        return $this->successWithData($data , 'Successfully Fetch PLans');
    }

    private function checkLocked($plan_id , $user_id){
        $lastWorkout = Workout::where('user_id', $user_id)->where('plan_id', $plan_id)->count();
        if($lastWorkout > 0){
            $lastWorkout = 'Unlocked';
        }else{
            $lastWorkout = 'Locked';
        }
        return $lastWorkout;
    }



    public function getPlans(){
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if($lastWorkout) {
            // Retrieve the next plan based on the plan_id from the last workout
            $nextPlan = Plan::with('videos')->where('user_id', $lastWorkout->user_id)
                            ->where('id', '>', $lastWorkout->plan_id)
                            ->orderBy('id', 'asc') // Assuming plans are ordered by ID
                            ->first(); // Get the next record
        } else {
            $nextPlan = plan::with('videos')->where('user_id' , $user->favourite_trainer)->first();
        }

        return $this->successWithData($nextPlan , 'Successfully Fetch PLans');
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

        return $this->successWithData($video , 'Successfully Fetch 10 Videos');
    }

    public function getPlan($id){
        $plan = Plan::with('videos')->find($id);
        return $this->successWithData($plan , 'Successfully Fetch Plan And Videos');
    }

    public function getTainerVideo(){
        $trainer_video = User::with('videos')->where('role_id' , 2)->first();
        return $this->successWithData($trainer_video , 'Successfully Fetch Trainer Videos');
    }

    public function getSpecificVideos($id){
        $video = video::find($id);
        return $this->successWithData($video , 'Successfully Fetch Specific Video');
    }

    public function progress_workout_store( Request $request ){
        $validator = Validator::make($request->all(), [
            'steps' => 'required',
            'kcal' => 'required',
            'bpm' => 'required',
            'sleep' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $record = workout::where('user_id', $user->id)->latest('created_at')->first();
        
        if (!$record || Carbon::now()->diffInDays($record->created_at) >= 1) {

            $newRecord = new workout();
            $newRecord->steps = $request->steps;
            $newRecord->kcal = $request->kcal;
            $newRecord->bpm = $request->bpm;
            $newRecord->sleep = $request->sleep;
            $newRecord->user_id = $user->id;
            $newRecord->save();
            
            return $this->successMessage('Workout Saved Successfully.');

        } else {
            $record->steps = $request->steps;
            $record->kcal = $request->kcal;
            $record->bpm = $request->bpm;
            $record->sleep = $request->sleep;
            $record->user_id = $user->id;
            $record->update();
        
            return $this->successMessage('Workout Updated Successfully.');
        }
        
    }
}
