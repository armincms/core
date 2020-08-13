<?php 
namespace Core\Armin\Http\Middlewares;

use Closure; 

class WordWideWebRedirector
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
        if($this->isWordWideWeb($request) || $this->isSubdomain($request) || !$this->needRedirect()) {
            return $next($request);
        }

        $request = $this->getWrodWideWebRequest($request);

        return redirect($request->fullUrl(), 301);
    }

    protected function isWordWideWeb($request)
    {
        return starts_with($request->getHost(), 'www.');
    }

    protected function isSubdomain($request)
    {
        $parts = explode('.', $request->getHost());

        return count($parts) > 2;
    }

    protected function needRedirect()
    {
        return (boolean) option('_force_www', true);
    }

    protected function getWrodWideWebRequest($request)
    {
        $host = 'www.' .$request->header('host');

        $request->headers->set('host', $host);  

        return $request;
    } 
}
