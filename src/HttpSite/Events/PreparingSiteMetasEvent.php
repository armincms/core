<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable;  
use Core\HttpSite\Component;

class PreparingSiteMetasEvent
{
    use Dispatchable, SerializesModels;

    public $metas = []; 
    public $site; 
    public $component; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($metas = [], $site, Component $component = null)
    {
        $this->metas    = $metas; 
        $this->site     = $site; 
        $this->component  = $component; 
    }  
}
 