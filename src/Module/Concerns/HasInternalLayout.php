<?php 
namespace Core\Module\Concerns;
 

trait HasInternalLayout
{ 

	private $internalLayouts = []; 

	/**
	 * Retrieve internall layout.
	 * 
	 * @param  string $key 
	 * @return Core\Layout\Layout
	 */
    public function internalLayout($key = 'layout') 
    {
        if(! isset($this->internalLayouts[$key])) {
            $this->internalLayouts[$key] = the_layout(
                $this->params($key, $this->defaultInternalLayout())
            );
        }

        return $this->internalLayouts[$key]; 
    } 

    /**
     * Retrieve all internal layouts.
     * 
     * @return \Illuminate\Support\Collection 
     */ 
    abstract public function defaultInternalLayout() : string;

    /**
     * Retrieve all internal layouts.
     * 
     * @return \Illuminate\Support\Collection 
     */
    public function internalLayouts()
    {
    	return collect($this->internalLayouts);
    } 

    public function internalAssets($layouts = []) : array
    {
        $layouts = is_array($layouts) ? $layouts : func_get_args();

        return collect($layouts)->map(function($name) {
            return the_layout($name)->css();
        })->flatten()->unique(function($asset) {
            return $asset->path();
        })->all();
    }

    public function internalPlugins($layouts = []) : array
    {
    	$layouts = is_array($layouts) ? $layouts : func_get_args();
        $plugins = [];

    	collect($layouts)->map(function($name) use (&$plugins) {
    		$plugins = array_merge($plugins, the_layout($name)->plugins());
    	})->flatten()->all();

        return $plugins;
    }
}