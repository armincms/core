<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable; 
use Core\HttpSite\Component;
use Closure;

class PreparingSiteModulesEvent
{
    use Dispatchable, SerializesModels;

    public $modules = []; 
    public $site; 
    public $component; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($modules = [], $site, Component $component = null)
    {
        $this->modules  = $modules; 
        $this->site     = $site; 
        $this->component  = $component; 
    }  

    public function filterModules(Closure $callback)
    {
        $this->modules = collect($this->modules)->filter($callback)->all();

        return $this;
    }
}
 