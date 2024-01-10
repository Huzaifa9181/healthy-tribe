<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\addiction;
use App\Models\question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = question::get();

            return DataTables::of($data)
                ->addColumn('option', function ($row) {
                    $option = json_decode($row->option);
                    return $options = implode(" , ", $option);
                })
                ->addColumn('addiction', function ($row) {
                    $addiction = addiction::find($row->addiction_id);
                    return $addiction->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('question.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="questions" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['option' , 'addiction' , 'action'])
                ->make(true);
        }
        return view('admin.question.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.question.index', ['title' => 'List Questions']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $addiction = addiction::all();
        return view('admin.question.create', ['title' => 'Create Question' , 'addiction' => $addiction]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'option' => 'required',
            'addiction' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $question = new question();
        $question->question = $request->question;
        $question->addiction_id = $request->addiction;
        $question->type = $request->type;
        $question->option = json_encode($request->option);
        $question->save();

        return redirect()->route('question.index')->with('success', 'Question created successfully!');
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
        $data = question::find($id);
        $count = count( json_decode($data->option));
        $addiction_option = json_decode($data->option);
        return view('admin.question.edit', ['title' => 'Update question' , 'data' => $data , 'addiction' => $addiction , 'count' => $count , 'addiction_option' => $addiction_option]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'option' => 'required',
            'addiction' => 'required',
            'id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $question = question::find($request->id);
        $question->question = $request->question;
        $question->type = $request->type;
        $question->addiction_id = $request->addiction;
        $question->option = json_encode($request->option);
        $question->update();

        return redirect()->route('question.index')->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $question = question::find($request->id);
    
            if ($question) {
                $question->delete();
                return response()->json(['message' => 'question deleted successfully']);
            } else {
                return response()->json(['message' => 'question not found'], 404);
            }
        }
    }
}
