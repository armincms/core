<?php
namespace Core\Layout; 

use Illuminate\Support\ServiceProvider;   
use Core\Layout\Repository;   

class LayoutServiceProvider extends ServiceProvider
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
            $this->app->afterResolving('layout.repository', function($repository, $app) { 
                $finder = $this->app['view']->getFinder();
                $loader = $app['translator']->getLoader();

                $repository->all()->each(function($layout) use ($finder, $loader) {
                    $finder->addNamespace(layout_hint_key($layout->name()), $layout->directory());

                    $loader->addNamespace(layout_hint_key($layout->name()), $layout->directory());
                });  
            });    
        }
    }   

    public function register()
    {     
        $this->registerLayoutEngine(); 

        $this->app->bind('armin.layout', function () {
            return app('layout.repository');
        });

        $this->app->singleton('layout.repository', function () {
            return new Repository($this->app['files']);
        });

        $this->commands(Console\LayoutMakeCommand::class);

        require 'functions.php';   
    }   

    public function provides()
    {
         return ['armin.layout', 'layout.repository'];
    } 


    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerLayoutEngine()
    {   
        $this->app->view->getEngineResolver()->register('layout', function () {  
            return new CompilerEngine($this->app['blade.compiler']);
        });

        $this->app->view->addExtension('layout.blade.php', 'layout'); 
    } 
}
