<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->role_id == 1){
                $data = User::where('role_id', '!=', 1)->with('role')->get();
            }else{
                $data = User::where('id', '!=', 1)->where('role_id', 3)->with('role')->get();
            }
            

            return DataTables::of($data)
                // ->addColumn('image', function ($row) {
                //     // Add any custom action buttons here
                //     $imageUrl = $row->image_link;
                //     return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
                // })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('users.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="users" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index', ['title' => 'List User']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create', ['title' => 'Create User']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8',
            'phone_number' => 'required',
            'dob' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        // Fetch the authenticated user
        $user = new User;
        // Update the user's profile
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');
        $user->dob = $request->input('dob');
        $user->country = $request->input('country');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->role_id = $request->input('role_id');
        $user->save();

        // Redirect back or to a success page
        if($request->input('role_id') == 2){
            $role = 'Trainer';
        }else{
            $role = 'User';
        }
        return redirect()->route('users.index')->with('success', $role.' created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        return view('admin.users.edit', ['title' => 'Update User' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->id), // Ignore the current user's email
            ],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'required',
            'dob' => 'required',
            'role_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        // Fetch the authenticated user
        $user = User::find($request->id);
        // Update the user's profile
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');
        $user->dob = $request->input('dob');
        $user->country = $request->input('country');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->role_id = $request->input('role_id');
        $user->update();

        // Redirect back or to a success page
        if($request->input('role_id') == 2){
            $role = 'Trainer';
        }else{
            $role = 'User';
        }
        return redirect()->route('users.index')->with('success', $role.' updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->input('id');
            $user = User::find($userId);
    
            if ($user) {
                $user->delete();
                return response()->json(['message' => 'User deleted successfully']);
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        }
    }

    public function trainer_profile(){
        $data = User::find(Auth::user()->id);
        $title = $data->name . ' Trainer Profile';
        return view('admin.users.profile' , compact('data' , 'title'));
    }

    public function trainer_profile_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::user()->id), // Ignore the current user's email
            ],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'required',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        // Fetch the authenticated user
        $user = User::find(Auth::user()->id);
        // Update the user's profile
        if ($request->hasFile('image')) {
            $validator->addRules([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/profile_images'), $filename);
            $user->image = 'assets/profile_images/' . $filename;
        } elseif ($request->has('hidden_image')) {
            $user->image = $request->input('hidden_image');
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');
        $user->dob = $request->input('dob');
        $user->country = $request->input('country');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->weight = $request->input('weight');
        $user->height = $request->input('height');
        $user->age = $request->input('age');
        $user->gender = $request->input('gender');
        $user->update();

        return back()->with('success', 'Trainer profile updated successfully!');
    }
}
