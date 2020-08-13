<?php 
namespace Core\Module\Repositories; 

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Helper;

class Repository 
{
	protected $files;

	static protected $modules;

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

    public function all()
    {
    	if(! isset(self::$modules)) { 
	    	self::$modules = $this->availableModules()->map(function($available) {
	    		$class = "{$available['namespace']}\\{$available['module']}";

                try {
                    return new $class;
                } catch (\Throwable $e) {
                    return null;
                } 
	    	})->filter();
    	}

    	return self::$modules; 
    }

    public function find(string $name)
    {
        return $this->all()->first(function($module) use ($name) {
            return $module->name() === $name;
        });
    } 

    /**
     * Get available modules.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function availableModules()
    {
        $modules = Collection::make($this->paths())->flatMap(function ($path) {
            return $this->files->directories($path);
        })->values()->map(function ($path) {  
        	$name = $this->files->basename($path); 
        	$module = studly_case($name); 
        	$namespace = "Module\\{$module}";  

            return compact('namespace', 'module', 'path');
        })->filter();  

        return tap($modules, function($modules) {
        	Helper::resolvePsr4($modules->pluck('path', 'namespace')->toArray());
        });
    }

    protected function paths()
    {
    	return Collection::make((array) config('armin.module.paths'))->filter(function($path) {  
    		return $this->files->isDirectory($path); 
    	})->prepend(module_path())->toArray();
    }  
}
