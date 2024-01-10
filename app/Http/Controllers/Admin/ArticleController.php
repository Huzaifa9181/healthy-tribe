<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = article::get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    $editButton = '<a href="' . route('article.edit', ['id' => $row->id]) . '" class="btn btn-info">Edit</a>';
                    $deleteButton = '<button class="btn btn-danger delete-user" data-id="' . $row->id . '" data-model="articles" data-toggle="modal" data-target="#deleteUserModal">Delete</button>';
                
                    return $editButton . ' ' . $deleteButton;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.article.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.article.index', ['title' => 'List Articles']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.article.create', ['title' => 'Create Article']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $article = new article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->save();

        return redirect()->route('article.index')->with('success', 'Article created successfully!');
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
        $data = article::find($id);
        return view('admin.article.edit', ['title' => 'Update Article' , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error' , $validator->errors());
        }

        $article = article::find($request->id);
        $article->title = $request->title;
        $article->description = $request->description;
        $article->update();

        return redirect()->route('article.index')->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $article = article::find($request->id);
    
            if ($article) {
                $article->delete();
                return response()->json(['message' => 'Article deleted successfully']);
            } else {
                return response()->json(['message' => 'Article not found'], 404);
            }
        }
    }

}
