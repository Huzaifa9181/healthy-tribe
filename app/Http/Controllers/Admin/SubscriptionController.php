<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{

    public function getSubscription(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscription::get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('subscription.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="subscription" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.subscription.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.subscription.index', ['title' => 'List Subscription']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscription.create', ['title' => 'Create Subscription']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'package' => 'required',
            'duration' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $subscription = new Subscription();
        // Update the subscription's profile
        $subscription->package = $request->input('package');
        $subscription->duration = $request->input('duration');
        $subscription->price = $request->input('price');
        $subscription->save();

        return redirect()->route('subscription.index')->with('success', ' Subscription created successfully!');
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
        $data = Subscription::find($id);
        return view('admin.subscription.edit', ['title' => 'Update Subscription' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        // Fetch the authenticated user
        $subscription = Subscription::find($request->id);
        // Update the subscription's profile
        $subscription->package = $request->input('package');
        $subscription->duration = $request->input('duration');
        $subscription->price = $request->input('price');

        $subscription->update();

        return redirect()->route('subscription.index')->with('success', 'Subscription updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->input('id');
            $subscription = Subscription::find($userId);
    
            if ($subscription) {
                $subscription->delete();
                return response()->json(['message' => 'Subscription deleted successfully']);
            } else {
                return response()->json(['message' => 'Subscription not found'], 404);
            }
        }
    }
}
