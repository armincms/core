<?php 
namespace Core\Template;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Core\Template\Template; 
 

class Repository  
{
	protected $files;

	protected $templates;

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

    public function all()
    {
    	if(! isset($this->templates)) {
    		$this->templates = $this->getTemplateFiles()->map(function($dir, $name) {  
                return $this->getTemplateInstance($name, $dir);
	    	});
    	} 

    	return $this->templates; 
    }

    public function template(string $template)
    {
    	return $this->all()->get($template);
    }

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function getTemplateFiles()
    {
        return Collection::make($this->paths())->flatMap(function ($path) {
            return $this->files->exists($path) ? $this->files->directories($path) : null;
        })->filter()->values()->mapWithKeys(function ($path) {   
            return [$this->files->basename($path) => $path];
        })->filter();
    }

    protected function paths()
    {
    	return Collection::make((array) config('armin.template.paths'))->filter(function($path) {  
    		return $this->files->isDirectory($path); 
    	})->prepend(template_path())->toArray();
    } 

    public function getTemplateInstance($name, $dir)
    {  
        return new Template($name, $dir); 
    } 
}