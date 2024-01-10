<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ChallengeController extends Controller
{
    public function index(){
        $title = 'Challenge List';
        return view('admin.challenge.index' , compact('title'));
    }

    public function create(){
        $title = 'Create Challenge';
        return view('admin.challenge.create' , compact('title'));
    }

    public function edit ($id){
        $title = 'Edit Challenge';
        $data = challenge::find($id);
        return view('admin.challenge.edit'  , compact('title' , 'data'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = challenge::all();

            return DataTables::of($data)
            ->addColumn('image', function ($row) {
                // Add any custom action buttons here
                $imageUrl = $row->image_link;
                return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
            })
            ->addColumn('action', function ($row) {
                // Add any custom action buttons here
                $editButton = '<a href="' . route('challenge.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="challenges" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
            
                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.challenge.index');
    }

    public function destroy( Request $request){
        if ($request->ajax()) {
            $challenge = challenge::find($request->id);
    
            if ($challenge) {
                if ($challenge->image) {
                    Storage::disk('public')->delete($challenge->image);
                }
            } 
            $challenge->delete();
            return response()->json(['message' => 'Challenge image deleted successfully']);
        }
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required', 
            'days' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $challenge = new challenge;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/challenge_images'), $filename);

            // Set the relative image path in the meal model
            $challenge->image = 'assets/challenge_images/' . $filename;
        }
        $challenge->title = $request->title;
        $challenge->description = $request->title;
        $challenge->days = $request->days;
        $challenge->save();

        return redirect()->route('challenge.index')->with('success', ' Challenge saved successfully!');

    }

    public function update (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required', 
            'days' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $challenge = challenge::find($request->id);
        if ($request->hasFile('image')) {
            $validator->addRules([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/challenge_images'), $filename);

            // Set the relative image path in the meal model
            $challenge->image = 'assets/challenge_images/' . $filename;
        }elseif ($request->has('hidden_image')) {
            $challenge->image = $request->input('hidden_image');
        }
        $challenge->title = $request->title;
        $challenge->description = $request->title;
        $challenge->days = $request->days;
        $challenge->update();

        return redirect()->route('challenge.index')->with('success', ' Challenge updated successfully!');

    }
}
