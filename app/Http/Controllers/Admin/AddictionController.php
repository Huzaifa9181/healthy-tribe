<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use App\Models\addiction_management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddictionController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = addiction::get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('addiction.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="addiction" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.addiction.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.addiction.index', ['title' => 'List Addictions']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.addiction.create', ['title' => 'Create Addiction']);
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

        $addiction = new addiction();
        $addiction->name = $request->name;
        $addiction->save();

        return redirect()->route('addiction.index')->with('success', 'Addiction created successfully!');
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
        $data = addiction::find($id);
        return view('admin.addiction.edit', ['title' => 'Update Addiction' , 'data' => $data]);
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

        $addiction = addiction::find($request->id);
        $addiction->name = $request->name;
        $addiction->update();

        return redirect()->route('addiction.index')->with('success', 'Addiction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $addiction = addiction::find($request->id);
    
            if ($addiction) {
                $addiction->delete();
                return response()->json(['message' => 'Addiction deleted successfully']);
            } else {
                return response()->json(['message' => 'Addiction not found'], 404);
            }
        }
    }

  
}
