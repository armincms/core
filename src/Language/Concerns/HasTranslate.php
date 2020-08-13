<?php 
namespace Core\Language\Concerns; 

trait HasTranslate
{  
    public function translates()
    {
        return $this->hasMany($this, 'assoc_key', 'assoc_key'); 
    }

    public function translate(string $locale)
    {
        $this->relationLoaded('translates') || $this->load('translates');

    	return $this->translates->where('language', $locale)->first(); 
    }

    public function trans($key, $locale=null, $default=null)
    {   
    	if($this->relationLoaded('translates')) {
    		return array_get(
    			$this->translate($locale ?? \App::getLocale()), $key, $default
    		);
    	}

    	return $default;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
    	if(str_contains($key, '::')) {
    		$parts = explode('::', $key);

            if($parts[0] == 'translate') return $this->translate($parts[1]);

    		return $this->trans($parts[0], $parts[1], array_get($parts, 3));
    	} 
    	
    	return parent::getAttribute($key);
    }


    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
    	$key = snake_case($method); 

        if (array_key_exists($key, $this->attributes)) {
            array_unshift($parameters, $key);

            return call_user_func_array([$this, 'trans'], $parameters);
        } 

        return call_user_func_array([parent::class, '__call'], func_get_args());
    }
} 