<?php 
namespace Core\Crud\Events; 

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PresentingResource
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $resource;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource; 
    }  
}
 