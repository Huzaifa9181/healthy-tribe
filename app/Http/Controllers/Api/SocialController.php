<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\message;
use App\Models\chatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\chatMessage;
use App\Events\GroupChatMessage;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Traits\HandleResponse;
use Illuminate\Support\Facades\Validator;

class SocialController extends Controller
{
    //
    use HandleResponse;

    public function sendMessage(Request $request){
        $user = Auth::guard('sanctum')->user();

        if ($request->hasFile('voice_message')) {
            $voice = $request->file('voice_message');
            // Generate a unique filename
            $filename = time() . '.' . $voice->getClientOriginalExtension();
            // Store the file in the public folder
            $voice->move(public_path('assets/voice_message'), $filename);
            // Set the relative image path in the meal model
            $voice_message = 'assets/voice_message/' . $filename;
        } 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Store the file in the public folder
            $image->move(public_path('assets/chat_images'), $filename);
            // Set the relative image path in the meal model
            $images = 'assets/chat_images/' . $filename;
        }
    
        $count = chatRoom::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->count();
        if($count === 0){
            $chat = new chatRoom;
            $chat->user_id = $user->id;
            $chat->another_user_id = $request->another_user_id;
            $chat->block = 0;
            $chat->save();
        }else{
            $chat = chatRoom::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->first();
        }

        $message = message::create([
            'user_id' => $user->id, 
            'message' => $request->input('message'), 
            'voice' => $voice_message ?? '', 
            'images' => $images ?? '', 
            'chat_room_id' => $chat->id, 
            'another_user_id' => $request->input('another_user_id') ?? 5, 
        ]);

        // Broadcast the new chat message to the 'chat' channel
        broadcast(new chatMessage($user->id , $message))->toOthers();

        $chat = message::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->latest()->get();
        return $this->successWithData($chat  , 'Successfully Send Message.');
    }

    public function AgentsendMessage(Request $request){
        $user = Auth::guard('sanctum')->user();

        if ($request->hasFile('voice_message')) {
            $voice = $request->file('voice_message');
            // Generate a unique filename
            $filename = time() . '.' . $voice->getClientOriginalExtension();
            // Store the file in the public folder
            $voice->move(public_path('assets/voice_message'), $filename);
            // Set the relative image path in the meal model
            $voice_message = 'assets/voice_message/' . $filename;
        } 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Store the file in the public folder
            $image->move(public_path('assets/chat_images'), $filename);
            // Set the relative image path in the meal model
            $images = 'assets/chat_images/' . $filename;
        }
    
        $count = chatRoom::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->count();
        if($count === 0){
            $chat = new chatRoom;
            $chat->user_id = $user->id;
            $chat->another_user_id = $request->another_user_id;
            $chat->block = 0;
            $chat->is_agent = 1;
            $chat->save();
        }else{
            $chat = chatRoom::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->first();
        }

        $message = message::create([
            'user_id' => $user->id, 
            'message' => $request->input('message'), 
            'voice' => $voice_message ?? '', 
            'images' => $images ?? '', 
            'chat_room_id' => $chat->id, 
            'another_user_id' => $request->input('another_user_id') ?? 5, 
        ]);

        // Broadcast the new chat message to the 'chat' channel
        broadcast(new chatMessage($user->id , $message))->toOthers();

        $chat = message::where('user_id' , $user->id)->where('another_user_id' , $request->input('another_user_id'))->latest()->get();
        return $this->successWithData($chat  , 'Successfully Send Message.');
    }

    public function chat_block(Request $request){
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'block' => 'required',
            'another_user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $message = chatRoom::where('user_id', $user->id)
                  ->where('another_user_id', $request->input('another_user_id')) // Provide the actual value here
                  ->first();
        if ($message) {
            $message->block = $request->input('block');
            $message->save(); // Use save() to update the record
        }

        return $this->successMessage('Successfully User Blocked.');
    }

    public function chat_delete(Request $request){
        $user = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), [
            'another_user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $message = chatRoom::where('user_id', $user->id)
                  ->where('another_user_id', $request->input('another_user_id')) // Provide the actual value here
                  ->delete();
        $chat = message::where('user_id', $user->id)
        ->where('another_user_id', $request->input('another_user_id')) // Provide the actual value here
        ->delete();

        return $this->successMessage('Successfully Chat Deleted.');
    }

    public function fetchChat($id = null){
        $user = Auth::guard('sanctum')->user();
        if($id){
            // $chat_room = chatRoom::where('id' , $id)->where('user_id' , $user->id)->first();
            $chat = message::where('chat_room_id' , $id)->latest()->get();
        }else{
            $chat = chatRoom::with('user:id,name' , 'anotherUser:id,name')->where('user_id' , $user->id)->get();
        }
        return $this->successWithData($chat  , 'Successfully Fetch All Chat.');

    }

    public function addMember(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'group_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        // Check member count
        $group = Group::find($request->group_id);
        // Check if group exists
        if (!$group) {
            return $this->badRequestResponse('Group not found.');
        }

        // Check member count
        if ($group->members()->count() >= 6) {
            return $this->badRequestResponse('Member limit reached.');
        }
        
        $group->members()->create([
            'user_id' => $user->id,
            'group_id' => $request->group_id
        ]);

        return $this->successMessage('Successfully Member Added.');
    }


    public function SendGroupMessage ( Request $request ) {
        $user = Auth::guard('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $grpMsg = new GroupMessage;
        $grpMsg->group_id = $request->group_id;
        $grpMsg->user_id = $user->id;
        $grpMsg->message = $request->message;
        $grpMsg->save();

        $group = Group::find($request->group_id);

        broadcast(new GroupChatMessage($grpMsg, $group))->toOthers();
        $grpMsgs = GroupMessage::with('user_id:id,name' , 'group_id:id,name,leader_id')->where('group_id' , $request->group_id)->latest()->get();
        return $this->successWithData($grpMsgs  , 'Successfully Send Message.');

    }

    public function group_fetchChat($id = null ){
        $user = Auth::guard('sanctum')->user();
        if($id){
            $data = GroupMessage::with('user_id:id,name' , 'group_id:id,name,leader_id')->where('group_id' , $id)->latest()->get();
        }else{
            $groupMemeber = GroupMember::where('user_id' , $user->id)->pluck('group_id');
            if($groupMemeber){
                $data = Group::with('leader_id:id,name' )->WhereIn('id' , $groupMemeber)->get();
            }
        }
        return $this->successWithData($data  , 'Successfully Fetch All Groups Chat.');

    }

    public function FetchAllGroup () {
        $data = Group::all();
        return $this->successWithData($data  , 'Successfully Fetch All Groups.');
    }


}
