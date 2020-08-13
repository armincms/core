<?php 
namespace Core\HttpSite;
 

class RouteHelper  
{      
	static public function routeToPattern(String $route)
	{
		$normalized = self::normalizeRoute($route); 

		return preg_replace('/\{[^}]+\}/', '([^\/]+)', $normalized);
	}

	static public function normalizeRoute(String $route)
	{
		return preg_replace("/\/|\\\\/", '\/', $route);
	}
}
