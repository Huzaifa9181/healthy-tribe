<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addiction;
use App\Models\motivation;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MotivationController extends Controller
{
 public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = motivation::get();

            return DataTables::of($data)
                ->addColumn('image', function ($row) {
                    // Add any custom action buttons here
                    $imageUrl = $row->image_link;
                    return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
                })
                ->addColumn('addiction_id', function ($row) {
                    return addiction::find($row->addiction_id)->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('motivation.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="motivation" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['image','addiction_id','action'])
                ->make(true);
        }
        return view('admin.motivation.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.motivation.index', ['title' => 'List Motivations']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $addiction = addiction::all();
        return view('admin.motivation.create', ['title' => 'Create Motivation' , 'addiction' => $addiction]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'addiction_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $motivation = new motivation();
        $motivation->addiction_id = $request->addiction_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/motivation_images'), $filename);

            // Set the relative image path in the meal model
            $motivation->image = 'assets/motivation_images/' . $filename;
        }
        $motivation->save();

        return redirect()->route('motivation.index')->with('success', 'Motivation created successfully!');
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
        $data = motivation::find($id);
        $addiction = addiction::all();
        return view('admin.motivation.edit', ['title' => 'Update Motivation' , 'data' => $data, 'addiction' => $addiction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'addiction_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $motivation = motivation::find($request->id);
        if ($request->hasFile('image')) {
            $validator->addRules([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }
            $image = $request->file('image');
            // Delete the old image if it exists
            if ($motivation->image) {
                Storage::disk('public')->delete($motivation->image);
            }
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Store the file in the public folder
            $image->move(public_path('assets/motivation_images'), $filename);
            // Set the relative image path in the meal model
            $motivation->image = 'assets/motivation_images/' . $filename;


        } elseif ($request->has('hidden_image')) {
            $motivation->image = $request->input('hidden_image');
        }
        $motivation->addiction_id = $request->addiction_id;
        $motivation->update();

        return redirect()->route('motivation.index')->with('success', 'Motivation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $motivation = motivation::find($request->id);
    
            if ($motivation) {
                if ($motivation->image) {
                    Storage::disk('public')->delete($motivation->image);
                }
                $motivation->delete();
                return response()->json(['message' => 'Motivation deleted successfully']);
            } else {
                return response()->json(['message' => 'Motivation not found'], 404);
            }
        }
    }
}
