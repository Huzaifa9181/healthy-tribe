<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use App\Models\addiction_management;
use App\Models\addiction_recovery;
use App\Models\comment;
use App\Models\currency;
use App\Models\motivation;
use App\Models\question;
use App\Models\resource_training;
use Illuminate\Http\Request;
use App\Traits\HandleResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AddictionController extends Controller
{

    use HandleResponse;
    public function addiction_type($id = null)
    {
        if ($id) {
            $addiction_type = addiction::find($id);
        } else {
            $addiction_type = addiction::all();
        }
        return $this->successWithData($addiction_type, 'Successfully Fetch Addiction Types');
    }

    public function addiction_question($id)
    {
        $question = question::with('addiction_id:id,name')->where('addiction_id', $id)->get();
        return $this->successWithData($question, 'Successfully Fetch All Question');
    }

    public function currency_list()
    {
        $currency = currency::all();
        return $this->successWithData($currency, 'Successfully Fetch All Currency');
    }

    public function motivate_list()
    {
        $motivation = motivation::latest()->get();
        return $this->successWithData($motivation, 'Successfully Fetch All Currency');
    }

    public function resource_training($id)
    {
        $resource_training = resource_training::with('addiction_id:id,name')->where('addiction_id', $id)->get();
        return $this->successWithData($resource_training, 'Successfully Fetch All Resource Training');
    }

    public function addiction_money_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'money' => 'required',
            'currency_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail(422, "Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();

        $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
        $addiction_recovery->money = $request->money;
        $addiction_recovery->currency_id = $request->currency_id;
        $addiction_recovery->update();

        return $this->successWithData($addiction_recovery, 'Successfully Saved Addiction Money.');
    }

    public function addiction_time_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail(422, "Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();

        $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
        $addiction_recovery->time = $request->time;
        $addiction_recovery->date = date('Y-m-d');
        $addiction_recovery->update();

        return $this->successWithData($addiction_recovery, 'Successfully Saved Addiction Time.');
    }

    public function addiction_track($type)
    {
        if ($type === 'time' || $type === 'Time') {
            $user = Auth::guard('sanctum')->user();
            $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
            $date = $addiction_recovery->date . ' ' . $addiction_recovery->time;
            $time = $this->calculateMinutesDifference($date) ?? '';
            return $this->successWithData($time, 'Successfully Fetch Time.');
        } elseif ($type === 'money' || $type === 'Money') {
            $user = Auth::guard('sanctum')->user();
            $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
            $date = $addiction_recovery->date . ' ' . $addiction_recovery->time;
            $money =  $this->calculateDaysDifference($date) * $addiction_recovery->money ?? '';
            return $this->successWithData($money, 'Successfully Fetch Money.');
        }
    }

    private function calculateDaysDifference($dateString)
    {
        try {
            // Parse the provided date string into a Carbon instance
            $providedDate = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);

            // Get the current date and time as a Carbon instance
            $currentDate = Carbon::now();

            // Calculate the difference in days
            $daysDifference = $currentDate->diffInDays($providedDate);
            return $daysDifference;
        } catch (\Exception $e) {
            // Return the error message if there's an exception
            return "Error: " . $e->getMessage();
        }
    }

    private function calculateMinutesDifference($timeString)
    {
        try {
            // Parse the provided time string into a Carbon instance
            $providedTime = Carbon::createFromFormat('Y-m-d H:i:s', $timeString);

            // Get the current time as a Carbon instance
            $currentTime = Carbon::now();

            // Calculate the difference in minutes
            $minutesDifference = $currentTime->diffInMinutes($providedTime);
            return $minutesDifference;
        } catch (\Exception $e) {
            // Return the error message if there's an exception
            return "Error: " . $e->getMessage();
        }
    }

    public function addiction_reset()
    {
        $user = Auth::guard('sanctum')->user();
        $addiction_recovery = addiction_recovery::where('user_id', $user->id)->delete();
        return $this->successMessage('Addiction Recovery Reset Successfully.');
    }

    public function addiction_management_store(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'option' => 'required|string|max:255',
            'addiction_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid credentials', 'errors' => $validator->errors()], 422);
        }

        // Check if the last record was inserted more than 24 hours ago
        $lastRecord = addiction_management::where('user_id', $user->id)
            ->latest()
            ->first();

        $moreThan24HoursAgo = now()->subHours(24);

        if (is_null($lastRecord) || $lastRecord->created_at <= $moreThan24HoursAgo) {
            $addictionManagement = new addiction_management();
            $addictionManagement->option = $request->option;
            $addictionManagement->option_id = $request->option_id; // Assuming this field should be included
            $addictionManagement->addiction_id = $request->addiction_id;
            $addictionManagement->date = $request->date;
            $addictionManagement->user_id = $user->id;
            $addictionManagement->save();

            return response()->json(['message' => 'Addiction Management Saved Successfully.'], 200);
        } else {
            return response()->json(['message' => 'A record has already been saved in the last 24 hours.'], 403);
        }
    }

    // public function addiction_management()
    // {
    //     $addiction_management = addiction_management::with('option_id:id,option')->get();
    //     return $this->successWithData($addiction_management, 'Successfully Fetch All Addiction Management.');
    // }

    public function AddictionManagementLikeCalender($id = null)
    {
        if ($id) {
            $addiction_management = addiction_management::find($id);
            if ($addiction_management->like == null) {
                $addiction_management->like = 1;
            } else {
                $addiction_management->increment('like');
            }
            $addiction_management->update();
            return $this->successWithData($addiction_management->like, 'Successfully Like Addiction Management Calender.');
        } else {
            return $this->successMessage('Unsuccessfully Not Like Progress Calender.');
        }
    }

    public function AddictionManagementUnlikeCalender($id = null)
    {
        if ($id) {
            $addiction_management = addiction_management::find($id);
            if ($addiction_management->like > 0) {
                $addiction_management->decrement('like');
            } else {
                $addiction_management->like = 0;
            }
            $addiction_management->update();
            return $this->successWithData($addiction_management->like, 'Successfully Like Addiction Management Calender.');
        } else {
            return $this->successMessage('Unsuccessfully Not Like Progress Calender.');
        }
    }

    public function progress_addiction_comment($id)
    {

        if ($id) {
            $comments = comment::where('addicton_management_id', $id)->get();
            return $this->successWithData($comments, 'Successfully Fetch All Comment For Addictions.');
        }
    }

    public function progress_addiction_comment_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'addicton_management_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail(422, "Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $comments = new comment;
        $comments->user_id = $user->id;
        $comments->comment = $request->comment;
        $comments->addicton_management_id = $request->addicton_management_id;
        $comments->save();

        return $this->successWithData($comments, 'Successfully Saved Comment For Addiction Management.');
    }

    public function progress_addiction_calender($id)
    {
        $calender = addiction_management::with('option_id:id,option', 'user_id:id,name', 'addiction_id:id,name')->where('addiction_id' , $id)->get();
        return $this->successWithData($calender, 'Successfully Fetch Addiction Management.');
    }
}
