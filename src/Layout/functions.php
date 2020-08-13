<?php   
if (! function_exists('layout_path')) { 
    /**
     * Get the path to the layouts folder.
     *
     * @param  string  $path
     * @return string
     */
    function layout_path($layout = '')
    { 
        return extension_path("layouts").($layout ? DS.$layout : $layout);
    }
}

if (! function_exists('layouts')) { 
    /**
     * Get the path to the layouts folder.
     *
     * @param  array|string  $group
     * @return string
     */
    function layouts($group = [])
    {      
        $group = is_array($group) ? $group : func_get_args(); 

        return app('armin.layout')->all()->filter(function($layout) use ($group) { 
            if(empty($group) || in_array('*', $layout->group()))  {
                return true;
            }

            return count(array_intersect($layout->group(), $group));
        });
    }
} 

if (! function_exists('layout')) {
    /**
     * Get the evaluated layout contents for the given layout.
     *
     * @param  string  $layout
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function layout(string $layout)
    {
        return the_layout($layout);
    }
} 

if (! function_exists('the_layout')) {
    /**
     * Get the evaluated layout contents for the given layout.
     *
     * @param  string  $layout
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function the_layout(string $layout)
    {
        return app('armin.layout')->layout($layout);
    }
}

if (! function_exists('layout_first')) {
    /**
     * Get first exists layout for the given layouts.
     *
     * @param  string|array  $layout 
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function layout_first()
    { 
        foreach (func_get_args() as $name) {
            if(! empty($name) && $layout = the_layout($name)) {
                return $layout;
            }
        } 

        $error = 'Not Found Layout(s) '. collect(func_get_args())->filter()->implode(', ') . '.';

        return abort(500, $error);
    }
} 

if (! function_exists('layout_hint_key')) {
    /**
     * Get hint key of layout view.
     *
     * @param  string  $layout 
     * @return string 
     */
    function layout_hint_key(string $layout)
    {
        return "layout-{$layout}";
    }
}
