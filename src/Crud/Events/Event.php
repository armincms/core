<?php

namespace Core\Crud\Events; 

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 
use Illuminate\Database\Eloquent\Model as Resource;
use Illuminate\Contracts\Auth\Authenticatable;

class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $resource;
    public $owner; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Resource $resource, Authenticatable $owner)
    {
        $this->resource = $resource;
        $this->owner = $owner;  
    } 

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
 