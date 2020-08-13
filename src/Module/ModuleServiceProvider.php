<?php
namespace Core\Module; 

use Illuminate\Support\ServiceProvider;  
use Core\Module\Contracts\InstanceRepository;  
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Core\Module\ModuleInstance as Module;
use Config;
use View; 

class ModuleServiceProvider extends ServiceProvider
{     
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {      
        if(! $this->app->runningInConsole()) {  
            $this->app->afterResolving('module.repository', function($repository, $app) { 
                $repository->all()->each(function($module) use ($app) {
                    $name = $module->name();

                    $app['view']->getFinder()->addNamespace(
                        module_hint_key($name) , module_path($name)
                    ); 
                    $app['translator']->getLoader()->addNamespace(
                        module_hint_key($name) , module_path($name)
                    );
                });  
            });        

            \ArminResource::register('module', Http\Controllers\ModuleController::class, [
                'except' => ['create', 'show']
            ]);
        } 


        \Config::set('armin.layout.paths.module', __DIR__.'/resources/layouts');
             
        $this->loadViewsFrom(__DIR__.DS.'resources'.DS.'views', 'module'); 
        $this->loadTranslationsFrom(__DIR__.DS.'resources'.DS.'lang', 'module'); 
        $this->loadMigrationsFrom(__DIR__.DS.'database'.DS.'migrations');
    } 
     
    /**
     * Register the service provider.
     */
    public function register()
    {
        require 'functions.php';  

        $this->commands(Commands\ModuleMakeCommand::class);

        $this->app->bind('armin.repository.module', function () {
            return Contracts\ModuleRepository::getInstance();
        });

        $this->app->bind('armin.instance.module', function () {
            return new Contracts\InstanceRepository;
        });

        $this->app->bind('module', function($app) {
            return new Factory($app['module.repository']);
        });

        $this->app->bind('module.instance', function($app) {
            return new Repositories\InstanceRepository($app['module.repository']);
        });

        $this->app->bind('module.repository', function($app) {
            return new Repositories\Repository($app['files']);
        }); 
    } 
 
    public function provides()
    {
        return [
            'armin.repository.module', 'armin.instance.module', 'module', 
            'module.instance', 'module.repository'
        ];
    }
}
