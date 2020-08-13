<?php 
namespace Core\App;

use Illuminate\Support\ServiceProvider;   
use Core\App\Repository\AppPageRepository; 

class AppServiceProvider extends ServiceProvider
{    

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {  
        $this->loadViewsFrom(__DIR__.'/resources/views', 'app');    
        $this->loadViewsFrom(__DIR__.'/resources/setting', 'section'); 
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'armin'); 
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'armin.app'); 
        $this->map();   
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('armin.repository.app-page', 'Core\App\Repository\AppPageRepository'); 

    }


    public function map()
    {  
        // $this->mapAuthRoutes(); 
        // $this->mapApiRoutes(); 
    }
    
    public function mapApiRoutes()
    {
        app('router')/*->middleware(config('admin.api.middleware', ['web', 'auth:api']))*/
                     ->prefix(config('admin.api.path_prefix', 'api'))
                     ->namespace(__NAMESPACE__.'\Http\Controllers\Api') 
                     ->group(__DIR__.DS.'api.php') ; 
    }

    public function mapAuthRoutes()
    { 
        app('router')->middleware(config('admin.panel.middleware', ['web', 'auth:admin']))
                     ->prefix(config('admin.panel.path_prefix', 'panel'). '/app')
                     ->namespace(__NAMESPACE__.'\Http\Controllers') 
                     ->group(__DIR__.DS.'routes.php');  
    } 

}