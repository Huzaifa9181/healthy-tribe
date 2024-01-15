<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use Illuminate\Http\Request;
use App\Models\addiction_type_option;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AddictionOptionController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = addiction_type_option::get();

            return DataTables::of($data)
                ->addColumn('addiction_id', function ($row) {
                    return addiction::find($row->addiction_id)->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('addiction_option.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="addiction/option" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['addiction_id' , 'action'])
                ->make(true);
        }
        return view('admin.addiction_type_option.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.addiction_type_option.index', ['title' => 'List Addiction Type Options']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $addiction = addiction::all();
        return view('admin.addiction_type_option.create', ['title' => 'Create Addiction Type Option' , 'addiction' => $addiction]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'option' => 'required',
            'addiction_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $addiction_type_option = new addiction_type_option();
        $addiction_type_option->option = $request->option;
        $addiction_type_option->addiction_id = $request->addiction_id;
        $addiction_type_option->save();

        return redirect()->route('addiction_option.index')->with('success', 'Addiction Type Option created successfully!');
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
        $addiction = addiction::all();
        $data = addiction_type_option::find($id);
        return view('admin.addiction_type_option.edit', ['title' => 'Update Addiction Type Option' , 'data' => $data , 'addiction' => $addiction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'option' => 'required',
            'addiction_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $addiction_type_option = addiction_type_option::find($request->id);
        $addiction_type_option->option = $request->option;
        $addiction_type_option->addiction_id = $request->addiction_id;
        $addiction_type_option->update();

        return redirect()->route('addiction_option.index')->with('success', 'Addiction Type Option updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $addiction_type_option = addiction_type_option::find($request->id);
    
            if ($addiction_type_option) {
                $addiction_type_option->delete();
                return response()->json(['message' => 'Addiction Type Option deleted successfully']);
            } else {
                return response()->json(['message' => 'Addiction Type Option not found'], 404);
            }
        }
    }
}
