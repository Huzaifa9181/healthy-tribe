<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use Illuminate\Http\Request;
use App\Models\resource_training;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class ResourceTrainingController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = resource_training::get();

            return DataTables::of($data)
                ->addColumn('pdf', function ($row) {
                    // Add any custom action buttons here
                    $pdf = '<a href="'.asset('public/'.$row->pdf).'" class="btn btn-success" target="_blank"><i class="fas fa-print"></i></a>';
                
                    return $pdf;
                })
                ->addColumn('addiction_id', function ($row) {
                    return addiction::find($row->addiction_id)->name ?? '';
                })
                ->addColumn('video', function ($row) {
                    // Add any custom action buttons here
                    $video = '<video controls style="width: 30%;">
                        <source src="'.asset('public/'.$row->video).'" type="video/mp4" >
                    </video>';
                
                    return $video;
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('resource_training.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="resource_trainings" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['addiction_id','pdf','video','action'])
                ->make(true);
        }
        return view('admin.resource_training.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.resource_training.index', ['title' => 'List Resource Trainings']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $addiction = addiction::all();
        return view('admin.resource_training.create', ['title' => 'Create Resource Training' , 'addiction' => $addiction]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'pdf' => 'required|file|mimes:pdf',
            'video' => 'required|file|mimes:mp4|max:20480', 
            'addiction_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $count = resource_training::count();
        if($count > 0){
            return back()->with('error' , "This Addiction Resource is already exsist.");
        }

        $resource_training = new resource_training();
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $filename = time() . '.' . $pdf->getClientOriginalExtension();
            
            // Store the file in the public folder
            $pdf->move(public_path('assets/resource_training_pdf'), $filename);

            // Set the relative image path in the meal model
            $resource_training->pdf = 'assets/resource_training_pdf/' . $filename;
        }

        if ($request->hasFile('video')) {
            $videos = $request->file('video');
            $filename = time() . '.' . $videos->getClientOriginalExtension();
            
            // Store the file in the public folder
            $videos->move(public_path('assets/resource_training_video'), $filename);

            // Set the relative image path in the meal model
            $resource_training->video = 'assets/resource_training_video/' . $filename;
        }
        $resource_training->addiction_id = $request->addiction_id;
        $resource_training->save();

        return redirect()->route('resource_training.index')->with('success', 'Resource Training created successfully!');
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
        $data = resource_training::find($id);
        $addiction = addiction::all();
        return view('admin.resource_training.edit', ['title' => 'Update Resource Training' , 'data' => $data , 'addiction' => $addiction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'addiction_id' => 'required',
            'pdf' => $request->hasFile('pdf') ? 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10000' : '',
            'video' => $request->hasFile('video') ? 'file|mimes:mp4|max:20480' : '',
        ]);
    
        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }
    
        $resource_training = resource_training::find($request->id);
        DB::beginTransaction();
    
        try {
            // Handling PDF file upload
            if ($request->hasFile('pdf')) {
                if ($resource_training->pdf) {
                    Storage::disk('public')->delete($resource_training->pdf);
                }
                $pdf = $request->file('pdf');
                $filename = time() . '.' . $pdf->getClientOriginalExtension();
                
                // Store the file in the public folder
                $pdf->move(public_path('assets/resource_training_pdf'), $filename);
    
                // Set the relative image path in the meal model
                $resource_training->pdf = 'assets/resource_training_pdf/' . $filename;
            }elseif ($request->has('hidden_pdf')) {
                $resource_training->pdf = $request->input('hidden_image');
            }

            if ($request->hasFile('video')) {
                if ($resource_training->video) {
                    Storage::disk('public')->delete($resource_training->video);
                }
                $videos = $request->file('video');
                $filename = time() . '.' . $videos->getClientOriginalExtension();
                
                // Store the file in the public folder
                $videos->move(public_path('assets/resource_training_video'), $filename);
    
                // Set the relative image path in the meal model
                $resource_training->video = 'assets/resource_training_video/' . $filename;
            }elseif ($request->has('hidden_video')) {
                $resource_training->video = $request->input('hidden_video');
            }
    
            $resource_training->addiction_id = $request->addiction_id;
            $resource_training->update();
            
            DB::commit();
            return redirect()->route('resource_training.index')->with('success', 'Resource Training updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred while updating the resource training.');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $resource_training = resource_training::find($request->id);
    
            if ($resource_training) {
                if ($resource_training->video) {
                    Storage::disk('public')->delete($resource_training->video);
                }
                if ($resource_training->pdf) {
                    Storage::disk('public')->delete($resource_training->pdf);
                }
                $resource_training->delete();
                return response()->json(['message' => 'Resource Training deleted successfully']);
            } else {
                return response()->json(['message' => 'Resource Training not found'], 404);
            }
        }
    }

}
