<?php     
use Core\Option\Option;

if (! function_exists('option')) { 
    /**
     * Add Or Retrieve Option.
     *
     * @param  string|array  $key
     * @param  mixed $default
     * @return mixed $value
     */
    function option($key = null, $default = null)
    {     
    	$cacheKey = 'armin_global_options';

    	if(is_array($key)) { 
    		$value 	= reset($key);
    		$key 	= key($key);

    		Option::updateOrCreate(compact('key'), compact('value'));

    		Cache::forget($cacheKey); 
    	}

    	$options = Cache::rememberForever($cacheKey, function () {
    		return Option::get()->pluck('value', 'key');
    	});    

    	return is_null($key)? $options : $options->get($key, $default);
    }
}  

if (! function_exists('option_exists')) { 
    /**
     * Check existance of option.
     *
     * @param  string  $key
     * @return boolean
     */
    function option_exists(String $key)
    {     
    	return option()->has($key); 
    }
} 
   