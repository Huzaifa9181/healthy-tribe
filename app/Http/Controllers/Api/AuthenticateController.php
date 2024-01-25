<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\achievement;
use App\Models\contents;
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
use App\Services\TwilioService;
use App\Traits\HandleResponse;
class AuthenticateController extends Controller
{
    use HandleResponse;
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => ['required'],
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $credentials = $request->only(['email', 'password']);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user has the "staff" role
            if ($user->role_id === 3) {
                $profile_update = $user->profile_update;
                $token = $user->createToken('MyApp')->plainTextToken;
                $user= User::where('email' , $request->email)->update(['remember_token' => $token]);
                return $this->successWithData(['token' => $token , 'profile' => $profile_update] , "access token" , 200 );
            }
        }

        return $this->badRequestResponse( "Invalid credentials" );
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
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
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
    
        return $this->successWithData($token , "access token" , 200 );
    }

    public function profile(Request $request){
        $validator = Validator::make($request->all(), [
            'gender' => 'required|string|max:255',
            'weight' => 'required|integer',
            'height' => 'required',
            'age' => 'required|integer',
            'sleep_quality' => 'required',
            'favourite_trainer' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $user = user::find($user->id);
        $user->gender = $request->gender;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->age = $request->age;
        $user->sleep_quality = $request->sleep_quality;
        $user->goal = $request->goal;
        $user->favourite_trainer = $request->favourite_trainer;
        $user->profile_update = 1;
        $user->update();
        return $this->successMessage('Profile Created Successfully');
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
        
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = user::find($user->id);
        if ($request->file('image')) {
            $validator->addRules([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            if ($validator->fails()) {
                return $this->fail( 422 ,"Invalid credentials", $validator->errors());
            }
            
            $image = $request->file('image');
            
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/profile_images'), $filename);

            // Set the relative image path in the meal model
            $user->image = 'assets/profile_images/' . $filename;
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

        return $this->successMessage('Profile Updated Successfully');
    }

    public function languages(){
        $data = language::all();
        return $this->successWithData($data , 'All Language Fetch');
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
        return $this->successWithData($data , 'Notifications Fetch');
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
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        
        // Generate a 5 digit pin
        $pin = rand(10000, 99999);
        $email = $request->email;
        $token = Str::random(60);
        $expiresAt = Carbon::now()->addSeconds(60);

        // Store token and pin in the password_resets table or your custom table
        DB::table('password_reset')->updateOrInsert(
            ['email' => $email],
            [
                'pin' => $pin, // Store the PIN
                'expires_at' => $expiresAt,
                'created_at' => now(), // Store the current timestamp
            ]
        );

        try {
            Mail::send('emails.pin', ['pin' => $pin], function ($message) use ($email) {
                $message->to($email)->subject('Your Password Reset PIN');
            });
        } catch (\Exception $e) {
            // Handle the exception or log it
            return $this->fail( 500 ,"Internal Server Error", 'Failed to send the PIN. Please try again.');
        }

        $data = ['expires_in' => 60];
        return $this->successWithData($data , 'PIN sent to your email.');
    }
    

    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|numeric|digits:5'
        ]);

        $pin = $request->pin;
        if($request->email){
            $validator->addRules([
                'email' => 'required|email|exists:users,email',
            ]);
        
            if ($validator->fails()) {
                return $this->fail( 422 ,"Invalid credentials", $validator->errors());
            }
            $email = $request->email;
            $record = DB::table('password_reset')->where('email', $email)->first();
            $user = User::where('email' , $email)->first();
        }elseif($request->phone_number){
            $validator->addRules([
                'phone_number' => 'required|integer',
            ]);
        
            if ($validator->fails()) {
                return $this->fail( 422 ,"Invalid credentials", $validator->errors());
            }
            $phone_number = $request->phone_number;
            $record = DB::table('password_reset')->where('phone_number', $phone_number)->first();
            $user = User::where('phone_number' , $phone_number)->first();
        }
        
        if ($record && $record->pin == $pin) {
            if ($record->expires_at > Carbon::now()) {
                $data = ['user_id' => $user->id];
                return $this->successWithData($data , 'PIN is valid.');
            } else {
                return $this->badRequestResponse('PIN has expired.');
            }
        }
        return $this->badRequestResponse('Invalid PIN.');
    }

    public function passwordChanged(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8', // Adjust the password rules as needed
            'confirm_password' => 'required|same:password',
            'user_id' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        // Get the authenticated user
        $user = User::find($request->user_id);

        // Update the user's password with the hashed value
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->successMessage('Password changed successfully');
    }

    public function sendTextMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|exists:users,phone_number', // Adjust the phone number rules as needed
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $twilio = new TwilioService();
        
        $pin = rand(10000, 99999);
        $expiresAt = Carbon::now()->addSeconds(60);

        // Store token and pin in the password_resets table or your custom table
        DB::table('password_reset')->updateOrInsert(
            ['phone_number' => $request->phone_number],
            [
                'pin' => $pin, // Store the PIN
                'expires_at' => $expiresAt,
                'created_at' => now(), // Store the current timestamp
            ]
        );

        $twilio->sendSMS($request->phone_number, 'Your otp is : '.$pin);
        return $this->successMessage('otp send successfully');
    }

    public function profileUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:173',
            'about' => 'required|string|max:188',
            'achievement' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();
        $user = User::find($user->id);
        $user->goal = $request->goal;
        $user->about = $request->about;
        $user->achievement = $request->achievement;
        $user->update();
        return $this->successMessage('Profile Updated Successfully.');
    }

    public function content(){
        $contents = contents::latest()->first();
        return $this->successWithData($contents , 'Fetch Terms of Services.');

    }
}
