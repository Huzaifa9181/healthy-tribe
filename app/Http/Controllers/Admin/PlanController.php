<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\plan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PlanController extends Controller
{
    public function plan_index(){
        $title = 'Plans';
        return view('admin.plans.index' , compact('title'));
    }

    public function plan_create(){
        $title = 'Plan Create';
        return view('admin.plans.create' , compact('title'));
    }

    public function plan_edit ($id){
        $title = 'Plan Edit';
        $data = plan::find($id);
        return view('admin.plans.edit'  , compact('title' , 'data'));
    }

    public function getPlans(Request $request){
        if ($request->ajax()) {
            $data = plan::get();

            return DataTables::of($data)
            ->addColumn('action', function ($row) {
                // Add any custom action buttons here
                $editButton = '<a href="' . route('trainer_video.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="videos" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
            
                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.plan.index');
    }

    public function plan_destroy( Request $request){
        if ($request->ajax()) {
            $userId = $request->input('id');
            $plan = plan::find($userId);
    
            // if ($plan) {
            //     if ($plan->path) {
            //         Storage::disk('public')->delete($plan->path);
            //     }
            // } 
            $plan->delete();
            return response()->json(['message' => 'Plan deleted successfully']);
        }
    }

    public function plan_store (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'cal' => 'required',
            'image' => 'required',
            'thumbnail' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $video = new plan;
        $video->trainer_id = Auth::user()->id;
        if ($request->hasFile('video')) {
            $videos = $request->file('video');
            $filename = time() . '.' . $videos->getClientOriginalExtension();
            
            // Store the file in the public folder
            $videos->move(public_path('assets/trainer_video'), $filename);

            // Set the relative image path in the meal model
            $video->path = 'assets/trainer_video/' . $filename;
        }
        $video->title = $request->title;
        $video->duration = $request->duration;
        $video->save();

        return redirect()->route('trainer_video.index')->with('success', ' Trainer video saved successfully!');

    }

    public function plan_update (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'duration' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $video = plan::find($request->id);
        $video->trainer_id = Auth::user()->id;
        if ($request->hasFile('video')) {
            $validator->addRules([
                'video' => 'required|file|mimes:mp4|max:20480', 
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }
            
            $videos = $request->file('video');
            if ($video->path) {
                Storage::disk('public')->delete($video->path);
            }
            $filename = time() . '.' . $videos->getClientOriginalExtension();
            
            // Store the file in the public folder
            $videos->move(public_path('assets/trainer_video'), $filename);

            // Set the relative image path in the meal model
            $video->path = 'assets/trainer_video/' . $filename;         
        }elseif ($request->has('hidden_video')) {
            $video->path = $request->input('hidden_video');
        }
        $video->title = $request->title;
        $video->duration = $request->duration;
        $video->update();

        return redirect()->route('trainer_video.index')->with('success', ' Trainer video updated successfully!');

    }
}
