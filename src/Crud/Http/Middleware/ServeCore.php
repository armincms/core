<?php

namespace Core\Crud\Http\Middleware;

use Core\Crud\Events\CoreServing; 

class ServeCore
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if($request->is('panel') || $request->is('panel/*')) { 
            CoreServing::dispatch();
        }

        return $next($request);
    } 
}
