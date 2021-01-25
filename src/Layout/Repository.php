<?php 
namespace Core\Layout;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
 

class Repository 
{
	protected $files;

	protected $layouts;

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

    public function all()
    {
    	if(! isset($this->layouts)) {
    		$this->layouts = $this->getLayoutFiles()->map(function($dir, $name) {  
                return $this->getLayoutInstance($name, $dir);
	    	});
    	} 

    	return $this->layouts; 
    }

    public function layout(string $layoutName)
    {
    	return $this->all()->first(function($layout) use ($layoutName) {
            return $layout->name() === $layoutName;
        });
    }

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function getLayoutFiles()
    {
        return Collection::make($this->paths())->flatMap(function ($path) {
            return $this->files->exists($path) ? $this->files->directories($path) : null;
        })->filter()->values()->mapWithKeys(function ($path) {   
            return [$this->files->basename($path) => $path];
        })->filter();
    }

    protected function paths()
    {
    	return Collection::make((array) config('armin.layout.paths'))->filter(function($path) {  
    		return $this->files->isDirectory($path); 
    	})->prepend(layout_path())->toArray();
    } 

    public function getLayoutInstance($name, $dir)
    {  
        return new Layout($name, $dir); 
    }

    public function getLayoutMeta($name, $dir)
    {
    	$file = "{$dir}/{$name}.php";   
    	$config = [];

    	if($this->files->exists($file)) {
    		$config = require $file;
    	} 

    	return $config; 
    }
}