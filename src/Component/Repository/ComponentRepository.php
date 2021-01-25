<?php 
namespace Core\Component\Repository;

use Composer\Autoload\ClassLoader;
use Cache; 
use File; 
use Artisan; 

class ComponentRepository
{ 
	/**
	 * Retrive all components and that configs.
	 *  
	 * @param  boolean $active 
	 * @return array       
	 */
	public function get($name = '')
	{    
		if(! empty($name)) return $this->retrive($name);

		return $this->getComponents();  
	} 

	/**
	 * Retrive a component and that configs by id.
	 * 
	 * @param  string $name  
	 * @return array       
	 */
	public function retrive($name)
	{
		return $this->get()->get($name);
	}  

    /**
	 * Sync components folder by database.
	 * 
	 * @return array
	 */
	public function getComponents()
	{ 
		$components = collect([]); 

		foreach ($this->folders() as $component => $path) {

			if ($this->isValid($component)) { 
				$components->put($component, collect(['provider' => $this->getProvider($component)]));
			} 
		} 

		return $components;
	} 

	/**
	 * Retrieve folder of all components.
	 * 
	 * @return array
	 */
	public function folders()
	{
		$folders = collect([]);

		if(! File::exists(component_path())) return $folders;

		foreach (File::directories(component_path()) as $path) {
			$name = File::name($path);

			$folders->put($name, $path);
		} 

		$this->resolveNamespaces($folders->keys()->toArray());

		return $folders;
	}


	/**
     * Register component namespace.
     * 
     * @param  string|null $component 
     * @return array
     */
    public function resolveNamespaces($pathes)
	{ 
		$loader = new ClassLoader;

		foreach ((array) $pathes as $path) { 
			$vendor 	= 'Component';
			$namespace 	= studly_case($path); 
		    // register classes with namespaces
		    $loader->addPsr4("{$vendor}\\{$namespace}\\", component_path($path)); 
		}
	
	    // activate the autoloader
	    $loader->register();   
	}

	/**
	 * Check validation of component.
	 * 
	 * @param  string  $component 
	 * @return boolean          
	 */
	public function isValid($component)
	{ 
		return class_exists($this->getProvider($component));
	}

	public function getProvider($component)
	{
		$name = studly_case($component);
		
		return "Component\\{$name}\\{$name}ServiceProvider";
	}

	public function install($component)
	{
		if($this->needInstall($component) && $installer = $this->getInstller($component)) {
			$installer->install(); 

			File::put(component_path($component.'/install'), time());

			Artisan::call('optimize');
		}  
	} 

	public function needInstall($component)
	{
		return ! File::exists(component_path($component).'/install');
	}

	public function getInstller($component)
	{
		$name = studly_case($component);
		
		$installer = "Component\\{$name}\\{$name}Installer";

		return class_exists($installer) ? app()->make($installer) : null;
	} 

	/**
	 * Forget cahced components.
	 * 
	 * @return string 
	 */
	public function forget()
	{
		Cache::forget($this->cacheKey());
	}

	/**
	 * Get cache storage key
	 * 
	 * @return string 
	 */
	public function cacheKey()
	{
		return 'armin.component';
	}

}