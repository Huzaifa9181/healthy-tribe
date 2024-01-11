<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use App\Models\addiction_recovery;
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
    public function addiction_type($id=null){
        if($id){
            $addiction_type = addiction::find($id);
        }else{
            $addiction_type = addiction::all();
        }
        return $this->successWithData($addiction_type , 'Successfully Fetch Addiction Types');
    }

    public function addiction_question($id){
        $question = question::with('addiction_id:id,name')->where('addiction_id' , $id)->get();
        return $this->successWithData($question , 'Successfully Fetch All Question');
    }

    public function currency_list(){
        $currency = currency::all();
        return $this->successWithData($currency , 'Successfully Fetch All Currency');
    }

    public function motivate_list(){
        $motivation = motivation::latest()->get();
        return $this->successWithData($motivation , 'Successfully Fetch All Currency');
    }
    
    public function resource_training($id){
        $resource_training = resource_training::with('addiction_id:id,name')->where('addiction_id' , $id)->get();
        return $this->successWithData($resource_training , 'Successfully Fetch All Resource Training');
    }

    public function addiction_money_store(Request $request){
        $validator = Validator::make($request->all(), [
            'money' => 'required',
            'currency_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();

        $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
        $addiction_recovery->money = $request->money;
        $addiction_recovery->currency_id = $request->currency_id;
        $addiction_recovery->update();

        return $this->successWithData($addiction_recovery , 'Successfully Saved Addiction Money.');
    }

    public function addiction_time_store(Request $request){
        $validator = Validator::make($request->all(), [
            'time' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();

        $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
        $addiction_recovery->time = $request->time;
        $addiction_recovery->date = date('Y-m-d');
        $addiction_recovery->update();

        return $this->successWithData($addiction_recovery , 'Successfully Saved Addiction Time.');
    }

    public function addiction_track($type){
        if($type === 'time' || $type === 'Time'){
            $user = Auth::guard('sanctum')->user();
            $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
            $date = $addiction_recovery->date .' '. $addiction_recovery->time ;
            return $this->calculateMinutesDifference($date) ?? '';
        }elseif($type === 'money' || $type === 'Money'){
            $user = Auth::guard('sanctum')->user();
            $addiction_recovery = addiction_recovery::where('user_id', $user->id)->first();
            $date = $addiction_recovery->date .' '. $addiction_recovery->time ;
            return $this->calculateDaysDifference($date) * $addiction_recovery->money ?? '';
        }
    }

    private function calculateDaysDifference($dateString) {
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

    private function calculateMinutesDifference($timeString) {
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
}
