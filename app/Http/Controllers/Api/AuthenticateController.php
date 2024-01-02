<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\language;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthenticateController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user has the "staff" role
            if ($user->role_id === 3) {
                $token = $user->createToken('MyApp')->plainTextToken;
                $user= User::where('email' , $request->email)->update(['remember_token' => $token]);
                return response()->json(['access_token' => $token], 200);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function signup(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required',
            'marital_status' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'institution_attended' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'phone_number' => 'required|integer',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'size:8' ],
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Create a new user
        $user = new User;
        $user->name = $request->name;
        $user->dob = $request->dob;
        $user->marital_status = $request->marital_status;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->institution_attended = $request->institution_attended;
        $user->occupation = $request->occupation;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->role_id = 3;
        $user->password = Hash::make($request->password);
        $user->save();

        // Authenticate the user
        Auth::login($user);
    
        // Generate an access token for the user
        $token = $user->createToken('MyApp')->plainTextToken;
    
        return response()->json(['access_token' => $token], 200); // Return a success response with the access token
    }

    public function profile(Request $request){
        $validator = Validator::make($request->all(), [
            'gender' => 'required|string|max:255',
            'weight' => 'required|integer',
            'height' => 'required',
            'age' => 'required|integer',
            'sleep_quality' => 'required',
            'goal' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::guard('sanctum')->user();
        $user = user::find($user->id);
        $user->gender = $request->gender;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->age = $request->age;
        $user->sleep_quality = $request->sleep_quality;
        $user->goal = $request->goal;
        $user->update();
        return response()->json(['message' => 'Profile Created Successfully'], 200); // Return a success response with the access token
    }

    public function profile_update(Request $request){
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|integer',
            'dark_mode' => 'required|integer',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Ignore the current user's email
            ],
            'weight' => 'required|integer',
            'height' => 'required',
            'gender' => 'required',
            'age' => 'required|integer',
        ]);
        
        $user = user::find($user->id);
        if ($request->file('image')) {
            $validator->addRules([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;    
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $request->name;
        $user->dark_mode = $request->dark_mode;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->gender = $request->gender;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->age = $request->age;
        $user->update();

        return response()->json(['message' => 'Profile Updated Successfully'], 200); // Return a success response with the access token
    }

    public function languages(){
        $data = language::all();
        return response()->json(['data' => $data], 200); // Return a success response with the access token
    }

    public function notification($status){
        $user = Auth::guard('sanctum')->user();
        if($status === 'New' || $status === 'new'){
            $data = notification::where('user_id', $user->id)
            ->latest() // Order by the most recent notifications first
            ->limit(5)  // Limit the result to the last 5 notifications
            ->get();
        }else{
            $data = notification::where('user_id' , $user->id)->get();
        }
        return response()->json(['data' => $data], 200); // Return a success response with the access token
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Generate a 4 digit pin
        $pin = rand(1000, 9999);
        $email = $request->email;
        $token = Str::random(60);
        $expiresAt = Carbon::now()->addSeconds(60);

        // Store token and pin in the password_resets table or your custom table
        DB::table('password_reset')->insert([
            'email' => $email,
            'pin' => $pin, // Store the PIN
            'expires_at' => $expiresAt,
            'created_at' => now(), // Store the current timestamp
        ]);

        try {
            Mail::send('emails.pin', ['pin' => $pin], function ($message) use ($email) {
                $message->to($email)->subject('Your Password Reset PIN');
            });
        } catch (\Exception $e) {
            // Handle the exception or log it
            return response()->json(['message' => 'Failed to send the PIN. Please try again.'], 500);
        }
        
        return response()->json(['message' => 'PIN sent to your email.', 'expires_in' => 60]);   
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'pin' => 'required|numeric|digits:4'
        ]);
        $email = $request->email;
        $pin = $request->pin;
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($record && $record->pin == $pin) {
            if ($record->expires_at > Carbon::now()) {
                return response()->json(['message' => 'PIN is valid.'], 200);
            } else {
                return response()->json(['message' => 'PIN has expired.'], 401);
            }
        }
        return response()->json(['message' => 'Invalid PIN.'], 401);
    }
}
