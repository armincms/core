<?php 
namespace Core\Plugin;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
 

class PluginRepository 
{
	protected $files;

	protected $plugins;

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

    public function all()
    {
    	if(! isset($this->plugins)) {
    		$this->plugins = $this->getPluginInstances();
    	} 

    	return $this->plugins; 
    }

    public function plugin(string $plugin)
    {
    	return $this->all()->get($plugin);
    }

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function getPluginInstances()
    {
        return Collection::make($this->pluginDirectories())->map(function ($path) {
            $name = $this->files->basename($path);
            $class = $this->qualifyNamespace($name);
 
            if(! class_exists($class)) {
                return null;
            }

            $plugin = new $class(app());

            if($plugin instanceof Plugin) {
                return $plugin;
            } 

            return null;
        })->filter();
    }

    protected function pluginDirectories()
    {
    	return tap($this->files->directories(plugin_path()), function($directories) {
            $vendors = Collection::make($directories)->mapWithKeys(function($directory) {
                $namespace = studly_case($this->files->name($directory)); 
                $vendor = "Plugin\\{$namespace}\\";

                return [$vendor => $directory];
            }); 

            \Helper::resolvePsr4($vendors->toArray());
        }); 
    }  

    public function qualifyNamespace(string $name)
    {
        $class = studly_case($name);

        return "Plugin\\{$class}\\{$class}"; 
    } 
}