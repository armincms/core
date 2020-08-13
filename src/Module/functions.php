<?php 

if (! function_exists('module_path')) { 
    /**
     * Get the path to the modules folder.
     *
     * @param  string  $path
     * @return string
     */
    function module_path($module = '')
    { 
        return extension_path("modules").($module ? DS.$module : $module);
    }
} 

if (! function_exists('module_hint_key')) {
    /**
     * Get hint key of module view.
     *
     * @param  string  $module 
     * @return string 
     */
    function module_hint_key(string $module)
    {
        return "module-{$module}";
    }
}
