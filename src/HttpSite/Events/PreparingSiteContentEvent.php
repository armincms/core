<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable; 
use Core\HttpSite\Component;

class PreparingSiteContentEvent
{
    use Dispatchable, SerializesModels;

    public $content = ''; 
    public $site; 
    public $component; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(String $content = null, $site, Component $component = null)
    {
        $this->content  = $content; 
        $this->site     = $site; 
        $this->component  = $component; 
    }  
}
 