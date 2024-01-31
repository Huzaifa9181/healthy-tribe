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

    public function getTrainers()
    {
        // Retrieve a unique collection of user IDs from the plans
        $uniquePlanUserIds = Plan::pluck('user_id')->unique();

        // Retrieve the last 10 trainers who have at least one plan
        $trainers = User::whereIn('id', $uniquePlanUserIds)  // Filter users who are associated with at least one plan
            ->where('role_id', 2)  // Filter users who are trainers
            ->latest()  // Ordering by the latest first
            ->limit(10)  // Limiting the results to 10
            ->get();  // Getting the collective

        return $this->successWithData($trainers, 'Successfully Fetch 10 Trainers');
    }

    public function getCategories($id = null)
    {
        if ($id) {
            $plan = plan::where('category_id', $id)->pluck('id');
            $categorie = categorie::find($id);
            $video = video::with(['user:id,name'])->select('id', 'title',  'path', 'duration', 'trainer_id', 'created_at')->whereIn('plan_id', $plan)->get();
            $data['categorie'] = $categorie;
            $data['video'] = $video;
            return $this->successWithData($data, 'Successfully Fetch 10 Categories');
        } else {
            $categorie = categorie::latest()  // Ordering by the latest first
                ->limit(10)  // Limiting the results to 10
                ->get();  // Getting the collective;
            return $this->successWithData($categorie, 'Successfully Fetch 10 Categories');
        }
    }

    public function FetchAllPlans()
    {
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if ($lastWorkout) {
            // Assuming that the plans are ordered in a way that reflects their sequence
            $plan = Plan::find($lastWorkout->plan_id);
            $plans = Plan::where('user_id', $plan->user_id)->get();

            $data = [];
            $unlockNext = false;
            foreach ($plans as $plan) {
                if ($lastWorkout && $plan->id == $lastWorkout->plan_id) {
                    $unlockNext = true;  // The next plan should be unlocked
                    $plan->locked = 'Unlocked';
                } else {
                    $plan->locked = $unlockNext ? 'Unlocked' : 'Locked';
                    $unlockNext = false; // Reset the flag after unlocking the next plan
                }
                $data[] = $plan;
            }
        } else {
            $plans = plan::where('user_id', $user->favourite_trainer)->get();

            $data = [];
            $unlockNext = false;
            $first = true; // Flag to track the first plan

            foreach ($plans as $plan) {
                if ($first) {
                    // Always unlock the first plan
                    $plan->locked = 'Unlocked';
                    $first = false;

                    // Check if the first plan is the last completed, then unlock next
                    if ($lastWorkout && $plan->id == $lastWorkout->plan_id) {
                        $unlockNext = true;
                    }
                } else {
                    // For subsequent plans, use the existing logic
                    if ($unlockNext) {
                        $plan->locked = 'Unlocked';
                        $unlockNext = false; // Reset after unlocking
                    } else {
                        $plan->locked = 'Locked';
                    }
                }
                $data[] = $plan;
            }
        }

        return $this->successWithData($data, 'Successfully Fetch Plans');
    }



    public function getPlans()
    {
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if ($lastWorkout) {
            // Retrieve the next plan based on the plan_id from the last workout
            $plan = plan::find($lastWorkout->plan_id);
            $nextPlan = Plan::with('videos')->where('user_id', $plan->user_id)
                ->where('id', '>', $lastWorkout->plan_id)
                ->orderBy('id', 'asc') // Assuming plans are ordered by ID
                ->first(); // Get the next record
        } else {
            $nextPlan = plan::with('videos')->where('user_id', $user->favourite_trainer)->first();
        }

        return $this->successWithData($nextPlan, 'Successfully Fetch PLans');
    }

    public function getVideos()
    {
        $user = Auth::guard('sanctum')->user();
        $lastWorkout = Workout::where('user_id', $user->id)->latest('id')->first();

        if ($lastWorkout) {
            // Retrieve the next plan based on the plan_id from the last workout
            $nextPlan = Plan::where('user_id', $lastWorkout->user_id)
                ->where('id', '>', $lastWorkout->plan_id)
                ->orderBy('id', 'asc') // Assuming plans are ordered by ID
                ->first(); // Get the next record
        } else {
            $nextPlan = plan::where('user_id', $user->favourite_trainer)->first();
        }

        if ($nextPlan) {
            $video = video::where('plan_id', $nextPlan->id)
                ->limit(10)  // Limiting the results to 10
                ->get();
        } else {
            $video = video::latest()
                ->limit(10)  // Limiting the results to 10
                ->get();
        }

        return $this->successWithData($video, 'Successfully Fetch 10 Videos');
    }

    public function getPlan($id)
    {
        $plan = Plan::with('videos')->find($id);
        return $this->successWithData($plan, 'Successfully Fetch Plan And Videos');
    }

    public function getTainerVideo()
    {
        $trainer_video = User::with('videos')->where('role_id', 2)->first();
        return $this->successWithData($trainer_video, 'Successfully Fetch Trainer Videos');
    }

    public function getSpecificVideos($id)
    {
        $video = video::find($id);
        return $this->successWithData($video, 'Successfully Fetch Specific Video');
    }

    public function progress_workout_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'required',
            'kcal' => 'required',
            'bpm' => 'required',
            'sleep' => 'required',
            'plan_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail2(422, "Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $record = workout::where('user_id', $user->id)->where('plan_id', $request->plan_id)->latest('created_at')->first();

        if (!$record || Carbon::now()->diffInDays($record->created_at) >= 1) {

            $newRecord = new workout();
            $newRecord->steps = $request->steps;
            $newRecord->kcal = $request->kcal;
            $newRecord->bpm = $request->bpm;
            $newRecord->sleep = $request->sleep;
            $newRecord->user_id = $user->id;
            $newRecord->plan_id = $request->plan_id;
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
