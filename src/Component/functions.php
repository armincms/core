<?php 

if (! function_exists('component_path')) { 
    /**
     * Get the path to the components folder.
     *
     * @param  string  $path
     * @return string
     */
    function component_path($component = '')
    { 
        return extension_path("components").($component ? DS.$component : $component);
    }
}
 

if (! function_exists('components')) { 
    /**
     * Get the path to the components folder.
     *
     * @param  string  $path
     * @return string
     */
    function components($component = null)
    { 
        $components = app('armin.repository.component')->get();

        return $component ? $components->get($component) : $components;
    }
}
 