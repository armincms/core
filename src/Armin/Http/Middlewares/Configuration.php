<?php 
namespace Core\Armin\Http\Middlewares;

use Closure; 

class Configuration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        \Config::set('app.log', 'daily');    
        \Config::set('app.locale', option('_default_locale', 'fa'));    
        \Config::set('app.timezone', option('_timezone', 'Asia/Tehran'));
        \Config::set('app.url', option('_base_domain', $request->getHost()));

        return $next($request);
    } 
}
