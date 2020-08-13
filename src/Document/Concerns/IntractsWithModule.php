<?php 
namespace Core\Document\Concerns; 


trait IntractsWithModule
{ 
	/**
	 * List of modules.
	 * 
	 * @var array
	 */
	protected $modules = []; 

	/**
	 * Rendered Modules.
	 * 
	 * @var array
	 */
	protected $renderedModules = [];      

	public function setModules(array $modules)
	{
		$this->modules = $modules; 

		return $this;
	}

	public function modules($positions = null)
	{  
		return collect($this->modules)->filter(function($module) use ($positions) { 
			return is_null($positions) || in_array($module->get('position'), (array) $positions);
		})->sortBy(function($module) {
			return $module->get('ordering');
		});
	}  

	public function renderedModules($position = null)
	{ 
		if(! isset($this->renderedModules[$position])) {
			$this->renderedModules[$position] = $this->modules($position)->map->toHtml()->implode('');
		} 

		return $this->renderedModules[$position]; 
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
