<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\currency;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = currency::get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('currency.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="currency" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.currency.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.currency.index', ['title' => 'List Currency']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.currency.create', ['title' => 'Create Currency']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $currency = new currency();
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->save();

        return redirect()->route('currency.index')->with('success', 'Currency created successfully!');
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
        $data = currency::find($id);
        return view('admin.currency.edit', ['title' => 'Update Currency' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $currency = currency::find($request->id);
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->update();

        return redirect()->route('currency.index')->with('success', 'Currency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $currency = currency::find($request->id);
    
            if ($currency) {
                $currency->delete();
                return response()->json(['message' => 'Currency deleted successfully']);
            } else {
                return response()->json(['message' => 'Currency not found'], 404);
            }
        }
    }
}
