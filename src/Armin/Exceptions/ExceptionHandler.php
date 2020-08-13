<?php

namespace Core\Armin\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException; 
use Armin\Exceptions\Handler;
use Exception;
use URL;

class ExceptionHandler extends Handler
{ 

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {  
        if ($exception instanceof NotFoundHttpException) {    
            return $this->notFoundResult($request, $exception); 
        } else if ($exception instanceof AccessDeniedException || $exception instanceof AuthorizationException) {    
            return $this->undenied($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if(! $exception->guards()) { 
            return $this->diffrentGuardLoggedIn();
        }   

        return  redirect()->guest($this->redirect($exception->guards()));
    }

    function diffrentGuardLoggedIn()
    {  
        return redirect()->guest(
            $this->redirect([\Auth::guard('user')->check() ? 'admin' : 'user'])
        );
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AccessDeniedException  $exception
     * @return \Illuminate\Http\Response
     */
    public function undenied($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }

        if(URL::previous() === URL::current()){
            return redirect()->route('panel')->withErrors($exception->getMessage());
        }

        return back()->withErrors($exception->getMessage());
    }

    public function redirect($guards)
    {
        $route = 'login';

        if ($guard = array_pop($guards)) {
            $route = $guard .'.'. $route;
        } 

        return route($route);
    }

    public function notFoundResult($request, $exception)
    {
        $site_setting = $request->session()->get('site_setting');

        $template = active_template();

        return $template && view()->exists($template->name .'::404') 
                    ? response()->view($template->name .'::404', ['errors' => $exception->getMessage()], 200)
                    : response()->view('errors.404', ['errors' => $exception->getMessage()], 200);
    }
}
