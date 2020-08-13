<?php 

if (! function_exists('plugin_path')) { 
    /**
     * Get the path to the plugins folder.
     *
     * @param  string  $path
     * @return string
     */
    function plugin_path($plugin = '')
    { 
        return extension_path("plugins").($plugin ? DS.$plugin : $plugin);
    }
}
 

if (! function_exists('armin_plugins')) { 
    /**
     * Retrive available plugins.
     * 
     * @return \Illuminate\Suppoer\Collection
     */
    function armin_plugins()
    { 
        return app('armin.repository.plugin')->all(); 
    }
}

if (! function_exists('armin_plugin')) { 
    /**
     * Retrive specific plugin.
     *
     * @param string $plugin [name of the plugin]
     * @return \Core\Plugin\Plugin
     */
    function armin_plugin(string $plugin)
    { 
        return armin_plugins()->get($plugin); 
    }
}
 