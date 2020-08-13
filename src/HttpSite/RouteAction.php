<?php 
namespace Core\HttpSite;
use Illuminate\Routing\RouteAction as Parser;


class RouteAction
{ 
    static public function parse($uri, $callback)
    { 
        if($callback instanceof \Closure) {  
            return [
                'home'      => $callback,
                'present'   => $callback
            ]; 
        }   

        if(is_string($callback) && class_exists($callback)) { 
            $callback .= '@present';
        }

        $callback = is_array($callback)? $callback : ['uses' => $callback];

        $action = Parser::parse($uri, $callback)['uses']; 

        return [
            'home'      => preg_replace('/@[^@]+/', '@home', $action),
            'present'   => $action,
        ];   
    }
}
