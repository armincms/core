<?php 

namespace Core\Document\Concerns;

use Illuminate\Support\Facades\Cache; 

trait IntractsWithModule
{ 
	/**
	 * List of modules.
	 * 
	 * @var array
	 */
	protected $modules = [];       

	public function setModules(array $modules)
	{
		$this->modules = $modules; 

		return $this;
	}

	public function modules($positions = null)
	{  
		return collect($this->modules)->filter(function($module) use ($positions) { 
			return is_null($positions) || in_array($module->get('position'), (array) $positions);
		})->unique->get('id')->sortBy(function($module) {
			return $module->get('ordering');
		});
	}  

	public function renderedModules($position = null)
	{  
		return $this->modules($position)->map(function($module) { 
			return Cache::sear($module->getModule()->cacheKey(), function() use ($module) {
				return $module->toHtml();
			});
		})->implode('');
	}

	public function loadModulePlugins()
	{  
		$order = - abs($this->plugins()->count());

		$this->modules()->map(function($module) use ($order)  { 
			$this->pushPlugins($module->plugins(), $order++);
		}); 

		return $this; 
	}


	public function loadModulesStyleSheet()
	{ 
		$order = - abs($this->sheets()->count());
		
		$this->pushSheet(new \Core\Module\StyleSheet, $order++); 

		return $this; 
	}


	public function getAvailableModules()
	{
	 	return app('armin.repository.module')->get();
	} 
}
