<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\contents;

class ContentController extends Controller
{
    public function index()
    {
        $data = contents::orderBy('id','DESC')->first();
        return view('admin.content.index', ['title' => 'Update Content', 'data' => $data]);
    }

    public function update(Request $request)
    {
        // Fetch the authenticated user
        $content = contents::find($request->id);
        if (!$content) {
            // If the record does not exist, create a new one
            $content = new contents();
        }
        $content->privacy = serialize($request->input('privacy'));
        $content->save();

        return redirect()->route('content.index')->with('success', 'Content updated successfully!');
    }
}
