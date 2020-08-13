<?php
namespace Core\Language; 

use Illuminate\Translation\FileLoader as Loader; 
use Illuminate\Filesystem\Filesystem; 

class FileLoader extends Loader
{ 

    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $loaded = [];

    /**
     * Create a new file loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $path
     * @return void
     */
    public function __construct(Filesystem $files, $path)
    { 
        parent::__construct($files, $path);
    }

    

    /**
     * Load a namespaced translation group.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    protected function loadNamespaced($locale, $group, $namespace)
    {
        if (isset($this->hints[$namespace])) {
            $merged = [];

            foreach((array) $this->hints[$namespace] as $path) {
                if($this->isLoadedPath($namespace, "{$path}.{$group}")) {
                    continue;
                } 

                $this->loaded[$namespace][] = "{$path}.{$group}";

                $lines = $this->loadPath($path, $locale, $group); 

                $merged = array_merge(
                    $this->loadNamespaceOverrides($lines, $locale, $group, $path),
                    $merged
                );
            } 

            return $merged;
        }

        return [];
    } 

    public function isLoaded($namespace)
    {
        if(! isset($this->loaded[$namespace])) { 
            return false;
        }

        return count($this->loaded[$namespace]) === count($this->hints[$namespace]);
    }

    public function isLoadedPath($namespace, $path)
    {
        if(! $this->isLoaded($namespace)) {
            return false;
        }

        return in_array($path, $this->loaded[$namespace]);
    }
    

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace][] = $hint;
    } 
}
