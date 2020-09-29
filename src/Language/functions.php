<?php    
if (! function_exists('default_locale')) { 
    /**
     * Armin application default locale.
     *
     * @param  string  $name
     * @return \Core\Langauge\Locale
     */
    function default_locale()
    {    
        return config('language.default_locale', env('LOCALE', 'fa')); 
    }
}  

if (! function_exists('configured_locales')) { 
    /**
     * Armin application default locale.
     * 
     * @param  boolean  $active
     * @return \Core\Langauge\Locale
     */
    function configured_locales($active = false)
    {       
        $filter = function($locale) use ($active) {
            return ! $active || (boolean) array_get($locale, 'active', true);
        };

        return collect(app('armincms.locales'))->keyBy('alias')->filter($filter)->toArray();
    }
}  

if (! function_exists('the_language')) { 
    /**
     * Retirve specific language instance.
     *
     * @param  string  $name
     * @return \Core\Langauge\Locale
     */
    function the_language($name)
    {    
        return language()->first(function($language) use ($name) {
            return $language->alias == $name;
        }); 
    }
}  

if (! function_exists('language')) { 
    /**
     * Retrieve all configured locales.
     *
     * @param  string  $name
     * @return \Illuminate\Support\Collection
     */
    function language($language = null, $active = false)
    {    
        if(! is_null($language)) {
            return the_language($language);
        } 

        return collect(configured_locales($active))->mapInto(
            \Core\Language\Locale::class
        ); 
    }
}  

if (! function_exists('active_language')) { 
    /**
     * Retrieve correspond language of current locale.
     * 
     * @return \Illuminate\Support\Collection
     */
    function active_language()
    {    
        return language()->first(function($language) {
            return $language->alias === app()->getLocale();
        });
    }
}   

if (! function_exists('armin_trans')) { 
    /**
     * Return translation if exists or not key of translation.
     *
     * @param  string  $path
     * @return string
     */
    function armin_trans(string $string = null, $replace = [], $locale = null)
    {     
        if(empty(trim($string))) {
            return trim($string);
        } else if(\Lang::has($string)) {
            $trans = trans($string, $replace, $locale);

            return is_array($trans)
                            ? array_get($trans, $string, title_case($string)) 
                            : $trans;
        } else if(str_contains($string, '::') && $parts = explode('::', $string)) { 
            return armin_trans(array_pop($parts), $replace, $locale);
        } else if(str_contains($string, '.') && $parts = explode('.', trans($string))) {
            $string = str_replace('_', ' ', array_pop($parts));

            if($replace = collect($replace)->filter()->implode(' - ')) {
                $string .= '-'.implode('-', (array) $replace);
            }

            return title_case($string);
        } 

        return $string ? __($string, $replace, $locale) : null;
    }   
}  

if (! function_exists('is_multilingual')) { 
    /**
     * Check If Site Is Multilingual.
     * 
     * @return boolean
     */
    function is_multilingual()
    {  
        return option('_multilingual_site', language(null, true)->count() > 1);
    }
}

if (! function_exists('localized_url')) { 
    /**
     * MAked Localized Url String.
     * 
     * @return boolean
     */
    function localized_url($url = '')
    {   
        $slugs = explode('/', $url); 
 
        return in_array(App::getLocale(), $slugs)? $url : App::getLocale().'/'.trim($url, '/'); 
    }
}

if (! function_exists('assoc_key')) { 
    /**
     * Make unique key for translated.
     * 
     * @return boolean
     */
    function assoc_key()
    {    
        return md5(uniqid(time(), true)); 
    }
}
