<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\categorie;
use App\Models\plan;
use App\Models\video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
{
    public function plan_index(){
        $title = 'Plans';
        return view('admin.plans.index' , compact('title'));
    }

    public function plan_create(){
        $title = 'Plan Create';
        $category = categorie::all();
        return view('admin.plans.create' , compact('title' , 'category'));
    }

    public function plan_edit ($id){
        $title = 'Plan Edit';
        $data = plan::find($id);
        $category = categorie::all();
        return view('admin.plans.edit'  , compact('title' , 'data','category'));
    }

    public function getPlans(Request $request){
        if ($request->ajax()) {
            $data = plan::get();

            return DataTables::of($data)
            ->addColumn('image', function ($row) {
                $imageUrl = $row->image_link;    
                return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
            })
            ->addColumn('category_id', function ($row) {
                $category_id = $row->category_id;    
                return categorie::find($category_id)->name ?? '';
            })
            ->addColumn('action', function ($row) {
                // Add any custom action buttons here
                $editButton = '<a href="' . route('plan.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="plans" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
            
                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.plan.index');
    }

    public function destroy( Request $request){
        if ($request->ajax()) {
            $userId = $request->input('id');
            $plan = plan::find($userId);
    
            if ($plan) {
                if ($plan->image) {
                    Storage::disk('public')->delete($plan->image);
                }
                if ($plan->thumbnail) {
                    Storage::disk('public')->delete($plan->thumbnail);
                }
            } 
            $plan->delete();
            return response()->json(['message' => 'Plan deleted successfully']);
        }
    }

    public function plan_store (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'duration' => 'required',
            'cal' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumb_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $plan = new plan;
        $plan->user_id = Auth::user()->id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/plan_image'), $filename);

            // Set the relative image path in the meal model
            $plan->image = 'assets/plan_image/' . $filename;
        }
        if ($request->hasFile('thumb_image')) {
            $thumb_image = $request->file('thumb_image');
            $filename = time() . '.' . $thumb_image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $thumb_image->move(public_path('assets/plan_thumbnail_image'), $filename);

            // Set the relative image path in the meal model
            $plan->thumbnail = 'assets/plan_thumbnail_image/' . $filename;
        }
        $plan->title = $request->title;
        $plan->cal = $request->cal;
        $plan->category_id = $request->category_id;
        $plan->duration = $request->duration;
        $plan->save();

        return redirect()->route('plan.index')->with('success', 'Plan saved successfully!');

    }
    

    public function plan_update (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'duration' => 'required',
            'cal' => 'required',
            'category_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $plan = plan::find($request->id);
        $plan->user_id = Auth::user()->id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validator->addRules([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }
            if ($plan->image) {
                Storage::disk('public')->delete($plan->image);
            }
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $image->move(public_path('assets/plan_image'), $filename);

            // Set the relative image path in the meal model
            $plan->image = 'assets/plan_image/' . $filename;
        }elseif ($request->has('hidden_image')) {
            $plan->image = $request->input('hidden_image');
        }

        if ($request->hasFile('thumb_image')) {
            $thumb_image = $request->file('thumb_image');
            $validator->addRules([
                'thumb_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            if ($validator->fails()) {
                return back()->with('error' , $validator->errors());
            }
            if ($plan->thumbnail) {
                Storage::disk('public')->delete($plan->thumbnail);
            }
            $image = $request->file('video');
            $filename = time() . '.' . $thumb_image->getClientOriginalExtension();
            
            // Store the file in the public folder
            $thumb_image->move(public_path('assets/plan_thumbnail_image'), $filename);

            // Set the relative image path in the meal model
            $plan->thumbnail = 'assets/plan_thumbnail_image/' . $filename;
        }elseif ($request->has('hidden_thumbnail_image')) {
            $plan->thumbnail = $request->input('hidden_thumbnail_image');
        }

        $plan->title = $request->title;
        $plan->cal = $request->cal;
        $plan->category_id = $request->category_id;
        $plan->duration = $request->duration;
        $plan->update();

        return redirect()->route('plan.index')->with('success', 'Plan updated successfully!');

    }


    // -------------- Plan Video -----------------------

    public function plan_video_index(){
        $title = 'Plans Video';
        return view('admin.plans.video_index' , compact('title'));
    }

    public function plan_video_create(){
        $title = 'Plan Video Create';
        $category = categorie::all();
        return view('admin.plans.video_create' , compact('title' , 'category'));
    }

    public function plan_video_edit ($id){
        $title = 'Plan Video Edit';
        $data = video::find($id);
        return view('admin.plans.video_edit'  , compact('title' , 'data'));
    }

    public function getPlansVideo(Request $request){
        if ($request->ajax()) {
            $data = video::whereNotNull('plan_id')->get();

            return DataTables::of($data)
            ->addColumn('path', function ($row) {
                // Add any custom action buttons here
                $video = '<video controls style="width: 30%;">
                    <source src="'.asset('public/'.$row->path).'" type="video/mp4" >
                </video>';
            
                return $video;
            })
            ->addColumn('action', function ($row) {
                // Add any custom action buttons here
                $editButton = '<a href="' . route('plan_video.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="videos" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
            
                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['path','action'])
                ->make(true);
        }
        
        return view('admin.plan.index');
    }

    public function plan_video_store (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'video' => 'required|file|mimes:mp4|max:20480', 
            'duration' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $video = new video;
        if ($request->hasFile('video')) {
            $videos = $request->file('video');
            $filename = time() . '.' . $videos->getClientOriginalExtension();
            
            // Store the file in the public folder
            $videos->move(public_path('assets/plan_video'), $filename);

            // Set the relative image path in the meal model
            $video->path = 'assets/plan_video/' . $filename;
        }
        $video->title = $request->title;
        $video->duration = $request->duration;
        $video->plan_id = Auth::user()->id;
        $video->save();
        return redirect()->route('plan_video.index')->with('success', 'Plan Video saved successfully!');
    }
    

    public function plan_video_update (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'duration' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $video = video::find($request->id);
        $video->plan_id = Auth::user()->id;
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
            $videos->move(public_path('assets/plan_video'), $filename);

            // Set the relative image path in the meal model
            $video->path = 'assets/plan_video/' . $filename;         
        }elseif ($request->has('hidden_video')) {
            $video->path = $request->input('hidden_video');
        }
        $video->title = $request->title;
        $video->duration = $request->duration;
        $video->update();

        return redirect()->route('plan_video.index')->with('success', 'Plan Video updated successfully!');

    }

    //  ------------- End Plan Video ----------------------------
   
}
