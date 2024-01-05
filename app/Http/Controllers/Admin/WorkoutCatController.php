<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\categorie;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class WorkoutCatController extends Controller
{

    public function get_workout_cat(Request $request)
    {
        if ($request->ajax()) {
            $data = categorie::get();

            return DataTables::of($data)
                ->addColumn('image', function ($row) {
                    // Add any custom action buttons here
                    $imageUrl = $row->image_link;
                    return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('workout_cat.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="workout_cat" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.workout_cat.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.workout_cat.index', ['title' => 'List Workout Categories']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.workout_cat.create', ['title' => 'Create Workout Categories']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $workout_cat = new categorie();
        // Update the workout_cat's profile
        $workout_cat->name = $request->input('name');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/workout_cat_images'), $filename);

            // Set the relative image path in the meal model
            $workout_cat->image = 'assets/workout_cat_images/' . $filename;
        }
        $workout_cat->save();

        return redirect()->route('workout_cat.index')->with('success', ' Workout categories created successfully!');
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
        $data = categorie::find($id);
        return view('admin.workout_cat.edit', ['title' => 'Update Workout Categories' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        // Fetch the authenticated user
        $workout_cat = categorie::find($request->id);
        // Update the workout_cat's profile
        $workout_cat->name = $request->input('name');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/workout_cat_images'), $filename);

            // Set the relative image path in the meal model
            $workout_cat->image = 'assets/workout_cat_images/' . $filename;

            // Delete the old image if it exists
            if ($workout_cat->image) {
                Storage::disk('public')->delete($workout_cat->image);
            }
        } elseif ($request->has('hidden_image')) {
            $workout_cat->image = $request->input('hidden_image');
        }

        $workout_cat->update();

        return redirect()->route('workout_cat.index')->with('success', 'Workout categories updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->input('id');
            $workout_cat = categorie::find($userId);
    
            if ($workout_cat) {
                if ($workout_cat->image) {
                    Storage::disk('public')->delete($workout_cat->image);
                }
                $workout_cat->delete();
                return response()->json(['message' => 'Workout categories deleted successfully']);
            } else {
                return response()->json(['message' => 'Workout categories not found'], 404);
            }
        }
    }
}
