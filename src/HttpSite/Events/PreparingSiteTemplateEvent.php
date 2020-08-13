<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable; 
use Core\HttpSite\Component;

class PreparingSiteTemplateEvent
{
    use Dispatchable, SerializesModels;

    public $template = ''; 
    public $site; 
    public $component; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($template = null, $site, Component $component = null)
    {
        $this->template = $template; 
        $this->site     = $site; 
        $this->component  = $component; 
    }  
}
 