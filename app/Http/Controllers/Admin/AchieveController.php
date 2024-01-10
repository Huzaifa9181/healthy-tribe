<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\achieve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AchieveController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = achieve::get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('achieve.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="achieve" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.achieve.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.achieve.index', ['title' => 'List Achieves']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.achieve.create', ['title' => 'Create Achieve']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $achieve = new achieve();
        $achieve->name = $request->name;
        $achieve->save();

        return redirect()->route('achieve.index')->with('success', 'Achieve created successfully!');
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
        $data = achieve::find($id);
        return view('admin.achieve.edit', ['title' => 'Update Achieve' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $achieve = achieve::find($request->id);
        $achieve->name = $request->name;
        $achieve->update();

        return redirect()->route('achieve.index')->with('success', 'Achieve updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $achieve = achieve::find($request->id);
    
            if ($achieve) {
                $achieve->delete();
                return response()->json(['message' => 'Achieve deleted successfully']);
            } else {
                return response()->json(['message' => 'Achieve not found'], 404);
            }
        }
    }
}
