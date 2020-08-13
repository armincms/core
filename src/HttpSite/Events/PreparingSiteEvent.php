<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable; 
use Core\HttpSite\Component;

class PreparingSiteEvent
{
    use Dispatchable, SerializesModels;

    public $site; 
    public $component; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($site, Component $component = null)
    {
        $this->site = $site; 
        $this->component = $component; 
    }  
}
 