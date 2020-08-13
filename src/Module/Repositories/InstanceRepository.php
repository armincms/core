<?php 
namespace Core\Module\Repositories; 

use Core\Module\ModuleInstance;
use Illuminate\Support\Collection;
use Helper;

class InstanceRepository 
{  
	protected $modules;

	public function __construct(Repository $modules)
	{
		$this->modules = $modules;
	}  

    public function activeModules()
    {
        return ModuleInstance::published()->get();
    }

    public function all()
    {
        return ModuleInstance::get();
    }

    public function locatedAt($active = false)
    {
        $locations = func_get_args();

        return $this->all()->filter(function($module) use ($locations, $active) {
            if($active && ! $module->isPublished()) {
                return false;
            }

            return call_user_func_array([$module, 'locatedAt'], $locations);
        }); 
    }
}
