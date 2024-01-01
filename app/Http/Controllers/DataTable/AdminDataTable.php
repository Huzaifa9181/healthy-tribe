<?php

namespace App\Http\Controllers\DataTable;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminDataTable extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::query(); // Replace 'YourModel' with your actual model name
            
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any custom action buttons here
                    // You can use HTML or generate buttons as needed
                    return '<button class="btn btn-info">Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.admins.index'); // Replace 'your-view' with your actual view name
    }
}
