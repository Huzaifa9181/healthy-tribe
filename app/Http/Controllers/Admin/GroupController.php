<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::get();

            return DataTables::of($data)
                ->addColumn('leader_id', function ($row) {
                    return $user = User::find($row->leader_id)->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('group.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="group" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['leader_id' , 'action'])
                ->make(true);
        }
        return view('admin.group.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.group.index', ['title' => 'List Groups']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('role_id' , '!=' , 1)->get();
        return view('admin.group.create', ['title' => 'Create Group' , 'user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'leader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $group = new group();
        $group->name = $request->name;
        $group->leader_id = $request->leader_id;
        $group->save();

        return redirect()->route('group.index')->with('success', 'Group created successfully!');
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
        $user = User::where('role_id' , '!=' , 1)->get();
        $data = Group::find($id);
        return view('admin.group.edit', ['title' => 'Update Group' , 'data' => $data , 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id' => 'required',
            'leader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $group = Group::find($request->id);
        $group->name = $request->name;
        $group->leader_id = $request->leader_id;
        $group->update();

        return redirect()->route('group.index')->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $group = Group::find($request->id);
    
            if ($group) {
                $group->delete();
                return response()->json(['message' => 'Group deleted successfully']);
            } else {
                return response()->json(['message' => 'Group not found'], 404);
            }
        }
    }
}
