<?php   
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

if (! function_exists('armin_version')) { 
    /**
     * Get version of armincms.
     *
     * @param  string  $path
     * @return string
     */
    function armin_version()
    { 
        $composer = json_decode(File::get(base_path('composer.json')));

        return optional($composer)->version;
    }
} 

if (! function_exists('armin_url')) { 
    /**
     * Make Url For Resource And Localized If Required.
     *
     * @param  string  $path
     * @return string
     */
    function armin_url($url = '')
    { 
        if(is_multilingual()) { 
            $url = localized_url($url);
        }
        
        return request()->secure() ? secure_url(trim_url($url)) : url(trim_url($url));
    }
} 

if (! function_exists('armin_asset')) { 
    /**
     * Url for assets.
     *
     * @param  string  $path
     * @return string
     */
    function armin_asset($link = '', $secure = true)
    {   
        return request()->secure() && $secure ? secure_asset(trim_url($link)) : asset(trim_url($link));
    }
} 

if (! function_exists('upload_url')) { 
    /**
     * Make url to upload path.
     *
     * @param  string  $path
     * @return string
     */
    function upload_url($link = '')
    {    
        return armin_asset(
            trim_url(config('armin.path.file', 'files').DS.$link)
        );
    }
} 

if (! function_exists('trim_url')) { 
    /**
     * Make valid url address.
     *
     * @param  string  $path
     * @return string
     */
    function trim_url($url = '', $separator = '/')
    {   
        return $separator.trim(preg_replace('/\\\\+|\/+/', $separator, $url), $separator);
    }
} 

if (! function_exists('trim_path')) { 
    /**
     * Make valid file path.
     *
     * @param  string  $path
     * @return string
     */
    function trim_path($path = '')
    {   
        return trim_url($path, DIRECTORY_SEPARATOR);
    }
} 

if (! function_exists('upload_path')) { 
    /**
     * Strage path of uploade files.
     *
     * @param  string  $path
     * @return string
     */
    function upload_path(string $path = null)
    {  
        return public_path(
            trim_path(config('armin.path.file', 'files').DS.$path)
        );
    }
}

if (! function_exists('upload_url')) { 
    /**
     * Access url of uploaded file.
     *
     * @param  string  $url
     * @return string
     */
    function upload_url(string $url = null)
    {  
        return Storage::disk('armin.public')->url( 
            trim_url(config('armin.path.file', 'files')."/{$url}")
        ); 
    }
} 

if (! function_exists('extension_path')) { 
    /**
     * Get the path to the extensions folder.
     *
     * @param  string  $path
     * @return string
     */
	function extension_path($extension = '')
	{ 
		return base_path("extensions").($extension ? DS.$extension : $extension);
	}
}

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


if (! function_exists('package_path')) { 
    /**
     * Get the path to the packages folder.
     *
     * @param  string  $path
     * @return string
     */ 
    /**
     * Get the path to the packages folder.
     *
     * @param  string  $path
     * @return string
     */
    function package_path($package = '')
    { 
        return extension_path("packages").($package ? DS.$package : $package);
    }
}   



if (! function_exists('resolve_namespaces')) {  
    /**
     * Register namespace by path and vendor name.
     * 
     * @param  array|string $pathes 
     * @param  string $vendor 
     * @return void
     */
    function resolve_namespaces(array $pathes, $vendor = null)
    { 
        $namespaces = [];

        foreach ((array) $pathes as $namespace => $path) {  
            $namespace  = studly_case($namespace); 
            $vendor     = studly_case($vendor ?? app()->getNamespace());
            // register classes with namespaces
            $namespaces["{$vendor}\\{$namespace}\\"] = $path; 
        } 

        \Helper::resolvePsr4($namespaces);
    }
}

if (! function_exists('armin_setting')) { 
    /**
     * Add Or Retrieve Option.
     *
     * @param  string|array  $key
     * @param  mixed $default
     * @return mixed $value
     */
    function armin_setting($key = null, $value = null)
    { 
        call_user_func_array('option', func_get_args());
    }
}

if (! function_exists('armin_icons')) { 
    /**
     * The available icons. 
     * 
     * @return array $value
     */
    function armin_icons()
    { 
        return require __DIR__.'/icons.php';
    }
}