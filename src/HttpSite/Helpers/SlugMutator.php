<?php 
namespace Core\HttpSite\Helpers;

class SlugMutator
{ 

    public static function name($resource)
    {
    	return optional($resource)->alias;
    }

    public static function id($resource)
    {
    	return optional($resource)->id;
    }

    public static function year($resource)
    {
    	return static::getDateTimeUrl('Y', $resource); 
    }  

    public static function month($resource)
    {
    	return static::getDateTimeUrl('m', $resource);
    } 

    public static function day($resource)
    {
    	return static::getDateTimeUrl('d', $resource); 
    }

    public static function shortdate($resource)
    {
        return static::getDateTimeUrl('Y m', $resource); 
    } 

    public static function fulldate($resource)
    {
        return static::getDateTimeUrl('Y m d', $resource); 
    }

    public static function stringdate($resource)
    {
    	return static::getDateTimeUrl('Y F D', $resource); 
    }

    public static function getDateTimeUrl($format, $resource)
    { 
		$format = preg_replace('/[^0-9a-zA-Z]+/', '/', $format);

		try {
			return $resource->{$resource->getCreatedAtColumn()}->format($format);
		} catch (\Exception $e) {
			return now()->format($format);
		}   
    }
}