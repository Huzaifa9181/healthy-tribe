<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\general_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
    // public function index(){
    //     $title = 'General Setting';
    //     return view('admin.general_setting.index' , compact('title'));
    // }

    // public function create(){
    //     $title = 'Create General Setting';
    //     return view('admin.general_setting.create' , compact('title'));
    // }

    // public function getData(Request $request){
    //     if ($request->ajax()) {
    //         $data = general_setting::all();

    //         return DataTables::of($data)
    //         ->addColumn('image', function ($row) {
    //             // Add any custom action buttons here
    //             $imageUrl = $row->image_link;
    //             return $imageUrl ? '<img src="'.$imageUrl.'" style="height: 50px; width: auto;">' : 'No Image';
    //         })
    //         ->addColumn('action', function ($row) {
    //             // Add any custom action buttons here
    //             $editButton = '<a href="' . route('general_setting.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
    //             return $editButton ;
    //         })
    //         ->rawColumns(['image','action'])
    //             ->make(true);
    //     }
    //     return view('admin.general_setting.index');
    // }

    // public function destroy( Request $request){
    //     if ($request->ajax()) {
    //         $general_setting = general_setting::find($request->id);
    
    //         if ($general_setting) {
    //             if ($general_setting->image) {
    //                 Storage::disk('public')->delete($general_setting->image);
    //             }
    //         } 
    //         $general_setting->delete();
    //         return response()->json(['message' => 'General Setting image deleted successfully']);
    //     }
    // }

    // public function store (Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->with('error' , $validator->errors());
    //     }

    //     $count = general_setting::count();
    //     if($count > 0){
    //         return back()->with('error' , "Setting Is Already Exsist.");
    //     }
    //     $general_setting = new general_setting;

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $filename = time() . '.' . $image->getClientOriginalExtension();
            
    //         // Store the file in the public folder
    //         $image->move(public_path('assets/logo'), $filename);

    //         // Set the relative image path in the meal model
    //         $general_setting->logo = 'assets/logo/' . $filename;
    //     }
    //     $general_setting->title = $request->title;
    //     $general_setting->save();

    //     return redirect()->route('general_setting.index')->with('success', ' General Setting saved successfully!');

    // }

    
    public function edit (){
        $title = 'Edit General Setting';
        $data = general_setting::first();
        return view('admin.general_setting.edit'  , compact('title' , 'data'));
    }

    public function update (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $general_setting = general_setting::first();
        if($general_setting){
            $general_setting = general_setting::first();
            if ($request->hasFile('image')) {
                $validator->addRules([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                
                if ($validator->fails()) {
                    return back()->with('error' , $validator->errors());
                }
    
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                
                // Store the file in the public folder
                $image->move(public_path('assets/logo'), $filename);
    
                // Set the relative image path in the meal model
                $general_setting->logo = 'assets/logo/' . $filename;
            }elseif ($request->has('hidden_image')) {
                $general_setting->logo = $request->input('hidden_image');
            }
            $general_setting->title = $request->title;
            $general_setting->facebook = $request->facebook;
            $general_setting->instagram = $request->instagram;
            $general_setting->twitter = $request->twitter;
            $general_setting->linkedin = $request->linkedin;
            $general_setting->update();
        }else{
            $general_setting = new general_setting();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                
                // Store the file in the public folder
                $image->move(public_path('assets/logo'), $filename);
    
                // Set the relative image path in the meal model
                $general_setting->logo = 'assets/logo/' . $filename;
            }
            $general_setting->title = $request->title;
            $general_setting->facebook = $request->facebook;
            $general_setting->instagram = $request->instagram;
            $general_setting->twitter = $request->twitter;
            $general_setting->linkedin = $request->linkedin;
            $general_setting->save();

        }

        return redirect()->route('general_setting.edit')->with('success', ' General Setting updated successfully!');

    }
}
