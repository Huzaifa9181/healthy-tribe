<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\article;
use App\Models\motivation;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $user = User::where('role_id' , 3)->get()->count();
        $article = article::count();
        $motivation = motivation::count();
        $trainer = User::where('role_id' , 2)->get()->count();
        return view('admin.dashboard',['title' => 'Home Page' , 'user_count' => $user , 'article' => $article , 'trainer' => $trainer , 'motivation' => $motivation]);
    }
}
