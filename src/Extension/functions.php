<?php 

use Composer\Autoload\ClassLoader;

if(! function_exists('resolve_psr4'))
{
    /**
     * Registers a set of PSR-4 directories for a given namespace.
     *
     * @param  array $paths
     * @return void
     */
	function resolve_psr4(array $paths)
	{   
        $loader = new ClassLoader;

        foreach ($paths as $namespace => $path) {  
            $loader->addPsr4($namespace, $path); 
        } 

        $loader->register(); 
	}
}