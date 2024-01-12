<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\community;
use App\Models\inner_reply;
use App\Models\reply;
use Illuminate\Http\Request;
use App\Traits\HandleResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    use HandleResponse;

    //
    public function comunity_list($addiction_id = null){
        if($addiction_id){
            $data = community::with('comment:id,comment,user_id,community_id,like' , 'comment.user_id:id,name' , 'comment.reply' , 'comment.reply.user_id:id,name' , 'comment.reply.InnerReply' , 'user_id:id,name' , 'addiction_id:id,name')->where('addiction_id' , $addiction_id)->get();
            // $ids = community::with('user_id:id,name' , 'addiction_id:id,name')->where('addiction_id' , $addiction_id)->pluck('id');
            // foreach($ids as $val){
            //     $data['community'] = community::with('user_id:id,name' , 'addiction_id:id,name')->find($val);
            //     $data['comment'] = comment::with('reply' , 'reply.user_id:id,name' , 'user_id:id,name')->select('id' , 'comment' , 'user_id' , 'community_id','like')->where('community_id' , $data['community']->id)->first();

            // }
            return $this->successWithData($data , 'Successfully Fetch All Commmunity.');
        }
    }

    public function comment_like($id = null){
        $comment = comment::find($id);
        if($comment->like == null){
            $comment->like = 1;
        }else{
            $comment->increment('like');
        }
        $comment->update();
        return $this->successWithData($comment->like , 'Successfully Like Comment.');
    }

    public function comment_unlike($id = null){
        $comment = comment::find($id);
        if($comment->like > 0){
            $comment->decrement('like');         
        }
        $comment->update();
        return $this->successWithData($comment->like , 'Successfully Unlike Comment.');
    }

    public function reply_like($id = null){
        $reply = reply::find($id);
        if($reply->like == null){
            $reply->like = 1;
        }else{
            $reply->increment('like');
        }
        $reply->update();
        return $this->successWithData($reply->like , 'Successfully Like Reply.');
    }

    public function reply_unlike($id = null){
        $reply = reply::find($id);
        if($reply->like > 0){
            $reply->decrement('like');         
        }
        $reply->update();
        return $this->successWithData($reply->like , 'Successfully Unlike Reply.');
    }

    public function community_store(Request $request){
        $validator = Validator::make($request->all(), [
            'caption' => 'required',
            'media' => 'required|file|mimes:mp4,jpeg,png,jpg,gif,avif|max:20480',
            'addiction_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $community = new community;
        $community->caption = $request->caption;
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $filename = time() . '.' . $media->getClientOriginalExtension();
            
            // Store the file in the public folder
            $media->move(public_path('assets/community'), $filename);

            // Set the relative image path in the meal model
            $community->path = 'assets/community/' . $filename;
        }
        $community->user_id = $user->id;
        $community->addiction_id = $request->addiction_id;
        $community->save();

        return $this->successMessage( 'Successfully Create Community.');
    }

    public function community_comment_store(Request $request){
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'community_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();
        $comment = new comment;
        $comment->comment = $request->comment;
        $comment->community_id = $request->community_id;
        $comment->user_id = $user->id;
        $comment->save();

        return $this->successMessage( 'Successfully Saved Community Comment.');
    }

    public function reply_store(Request $request){
        $validator = Validator::make($request->all(), [
            'reply' => 'required',
            'comment_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();
        $reply = new reply();
        $reply->reply = $request->reply;
        $reply->comment_id = $request->comment_id;
        $reply->user_id = $user->id;
        $reply->like = 0;
        $reply->save();

        return $this->successMessage( 'Successfully Saved Community Reply.');
    }

    public function inner_reply_store(Request $request){
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'reply_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }
        $user = Auth::guard('sanctum')->user();
        $inner_reply = new inner_reply();
        $inner_reply->message = $request->message;
        $inner_reply->reply_id = $request->reply_id;
        $inner_reply->user_id = $user->id;
        $inner_reply->like = 0;
        $inner_reply->save();

        return $this->successMessage( 'Successfully Saved Community Inner Reply.');
    }


    public function InnerReply_like($id = null){
        $reply = inner_reply::find($id);
        if($reply->like == null){
            $reply->like = 1;
        }else{
            $reply->increment('like');
        }
        $reply->update();
        return $this->successWithData($reply->like , 'Successfully Like Inner Reply.');
    }

    public function InnerReply_unlike($id = null){
        $reply = inner_reply::find($id);
        if($reply->like > 0){
            $reply->decrement('like');         
        }
        $reply->update();
        return $this->successWithData($reply->like , 'Successfully Inner Unlike Reply.');
    }
}
