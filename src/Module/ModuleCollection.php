<?php 
namespace Core\Module;  

use Illuminate\Database\Eloquent\Collection;
use Core\Module\Module;


/**
 * summary
 */
class ModuleCollection extends Collection
{  

    public function modules()
    { 
    	return $this->map(function($instance) {
            return app('module')->make($instance);
        });
    } 
}