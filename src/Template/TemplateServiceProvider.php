<?php
namespace Core\Template; 

use Illuminate\Support\ServiceProvider;    

class TemplateServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__.DS.'config.php', 'armin.template');
        $this->mergeConfigFrom(__DIR__.DS.'icon.php', 'armin.icon');  
        $this->loadViewsFrom(__DIR__.DS.'resources'.DS.'views', 'template');  
        $this->loadTranslationsFrom(__DIR__.DS.'resources'.DS.'lang', 'template');   

        if(! $this->app->runningInConsole()) { 
            $this->app->afterResolving('template.repository', function($repository, $app) { 
                $finder = $this->app['view']->getFinder();
                $loader = $app['translator']->getLoader();

                $repository->all()->each(function($template) use ($finder, $loader) {
                    $namespace = template_hint_key($template->name());
                    $directory = $template->directory();

                    $finder->addNamespace($namespace, $directory); 
                    $loader->addNamespace($namespace, $directory);
                });  
            });    
        }
    }


    function register()
    {    
        $this->commands(Console\TemplateMakeCommand::class);

        require 'functions.php';

        $this->app->bind('template', function($app) {
            return new Factory($app['template.repository']);
        });

        $this->app->bind('template.repository', function($app) {
            return new Repository($app['files']);
        });

        \ArminResource::register('template', Http\Controllers\TemplateController::class, [
            'only' => ['index', 'update', 'edit']
        ]); 
    } 

    public function provides()
    {
        return [
            'template', 'template.repository'
        ];
    }
}
