<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\HandleResponse;

class PostController extends Controller
{
    //
    use HandleResponse;

    public function getPost(Request $request){
        $post = post::latest();

    }

    public function post_store(Request $request){
        $validator = Validator::make($request->all(), [
            'caption' => 'required',
            'image' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();

        $post = new post();
        $post->caption = $request->caption;
        if ($request->file('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Store the file in the public folder
            $image->move(public_path('assets/post_images'), $filename);

            // Set the relative image path in the meal model
            $post->image = 'assets/post_images/' . $filename;
        }
        $post->user_id = $user->id;
        $post->save();

        return $this->successMessage('Post Created Successfully.');

    }

    public function post_like($id){
        $post = post::find($id);
        if($post->like == null){
            $post->like = 1;
        }else{
            $post->increment('like');
        }
        $post->update();
        return $this->successWithData($post->like , 'Successfully Add Like Workout.');
    }

    public function post_unlike($id){
        $post = post::find($id);
        if($post->like == null  && $post->like > 0){
            $post->like = 0;
        }else{
            $post->decrement('like');
        }
        $post->update();
        return $this->successWithData($post->like , 'Successfully Add Like Workout.');
    }

    public function post_comment ($id) {
        $user = Auth::guard('sanctum')->user();
        $comments = comment::where('user_id', $user->id)
        ->where('post_id', $id)
        ->get();
        return $this->successWithData($comments , 'Successfully Fetch All Comment For this Post.');
    }
    
    public function post_comment_store(Request $request){
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'post_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->fail( 422 ,"Invalid credentials", $validator->errors());
        }

        $user = Auth::guard('sanctum')->user();
        $comments = new comment;
        $comments->user_id = $user->id;
        $comments->comment = $request->comment;
        $comments->post_id = $request->post_id;
        $comments->save();
        
        return $this->successWithData($comments , 'Successfully Saved Comment For Post.');
    }
}
