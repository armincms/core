<?php 
namespace Core\Language\Concerns;

use Core\Language\Translate; 
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslation
{  
    protected static $eagerTranslations = true;

    public function initializeHasTranslation()
    {
        if($this->autoEagerLoad())  { 
            $this->with[] = 'translates'; 
        }

        return $this;
    }

    public function autoEagerLoad()
    {
        if(! isset(self::$eagerTranslations)) return true;

        return (boolean) self::$eagerTranslations;
    }

    public function translates()
    {     
        $instance = $this->getTranslationModel();

        $relatedTable = $instance->getTable().'.'.$this->getForeignKey();

        return $this->newHasMany(
            $instance->newQuery(), $this, $relatedTable, $this->getKeyName()
        ); 
    } 

    protected function getTranslationModel()
    {
        return (new Translate)->setTable($this->translationTable());
    }
    
    protected function translationTable()
    { 
        return property_exists($this, 'translationTable') 
                        ? $this->translationTable 
                        : snake_case(class_basename($this)). '_translations';
    }

    public function translate(string $locale)
    { 
        $this->relationLoaded('translates') || $this->load('translates');

    	return $this->translates->where('language', $locale)->first(); 
    }

    public function trans(string $key, string $locale=null, $default=null)
    {    
        if(! $this->relationLoaded('translates')) {
            return $default; 
        }

        return array_get(
            $this->translate($locale ?? \App::getLocale()), $key, $default
        );
    }

    public function forceTrans(string $key, string $locale=null, $default=null)
    {      
        $translate = $this->trans($key);

    	return is_null($translate)
                    ? $this->getDefaultAttributeTranslation($key, $default) 
                    : $translate;
    }

    public function getDefaultAttributeTranslation($key, $default)
    {
        $translate = $this->translate(\App::getLocale()) ?? $this->translates->first();
        
        return array_get($translate, $key, $default);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key, $default = null)
    {
    	if(str_contains($key, '::')) {
    		$parts = explode('::', $key); 

            if($parts[0] == 'translate') return $this->translate($parts[1]);

    		return $this->trans($parts[1], $parts[0], array_get($parts, 3));
    	} 

        if($value = parent::getAttribute($key, $default)) {
            return $value;
        }  

        if($this->relationLoaded('translates')) {
            $forced = array_get($this->translates->first(), $key, $default);

            return $this->trans($key, null, $forced);
        }

        return $value; 
    } 

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {  
        $parts = explode('::', $key);

        if($translate = $this->translates->firstWhere('language', $parts[0])) { 
            return $translate->setAttribute(str_after($key, '::'), $value);
        }

        return parent::setAttribute($key, $value); 
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

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    { 
        $translation = $this->translate(app()->getLocale())->toArray();

        return collect($translation)->map(function($value, $key) {
            if($this->hasGetMutator($key)) {
                return $this->mutateAttribute($key, $value);
            }

            return $value;
        })->merge(parent::toArray())->toArray(); 
    }
} 