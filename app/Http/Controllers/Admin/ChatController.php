<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\chatRoom;
use App\Models\message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    //
    public function index () {
        $chats = chatRoom::with('user:id,name,image')->where('is_agent' , 1)->get();        
        $title = 'Chat App';
        return view('chat' , compact('chats' , 'title'));
    }
}
