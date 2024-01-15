<?php

namespace App\Events;

use App\Models\notification as ModelsNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class notification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user_id;
    public $title;
    public $description;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id , $title , $description)
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->description = $description;

        $notification = new ModelsNotification() ;
        $notification->title = $title;
        $notification->description = $description;
        $notification->user_id = $user_id;
        $notification->save();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notification.' . $this->user_id);
    }
}
