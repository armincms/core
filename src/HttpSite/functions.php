<?php     
if (! function_exists('armin_sites')) { 
    /**
     * Get the path to the sites folder.
     *
     * @param  string  $path
     * @return string
     */
    function armin_sites($active = false)
    {    
        return app('armin.repository.site')->get()->filter(function($site) use ($active) {
            return !$active || $site->published();
        }); 
    }
} 
   
if (! function_exists('presentable_layout')) { 
    /**
     * Retrieve Presentable layout.
     *
     * @param  string  $presentable
     * @param  string  $type
     * @return Object
     */
    function presentable_layout(string $presentable, string $type)
    {    
        $layoutName     = config("http-site.presentables.{$presentable}.layouts.{$type}.layout"); 

        return ! empty($layoutName) ? layouts($layoutName) : null; 
    }
}  
   
if (! function_exists('presentable_layout_config')) { 
    /**
     * Retrieve Presentable layout_config.
     *
     * @param  string  $presentable
     * @param  string  $type
     * @return array
     */
    function presentable_layout_config(string $presentable, string $type)
    {      
        return (array) config("http-site.presentables.{$presentable}.layouts.{$type}.config", []); 
    }
}   
   
if (! function_exists('current_site')) { 
    /**
     * Retrieve Presentable layout_config.
     *
     * @param  void
     * @return Site $site
     */
    function current_site()
    {      
        return app(Core\HttpSite\Http\Requests\SiteRequest::class)->site(); 
    }
}   
   
if (! function_exists('current_section')) { 
    /**
     * Retrieve Presentable layout_config.
     *
     * @param  void
     * @return Site $site
     */
    function current_section()
    {      
        return app(Core\HttpSite\Http\Requests\SiteRequest::class)->section(); 
    }
}   