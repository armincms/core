<?php 
namespace Core\Extension;

use Illuminate\Support\ServiceProvider;


class ExtensionServiceProvider extends ServiceProvider
{
	/**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {       

        // $this->loadViewsFrom(__DIR__.'/resources/views', 'extension'); 
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'extension'); 
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');  
 
        // $this->mapAuthRoutes(); 

        \Menu::get('bigMenu')->add('extension::title.extensions', ['nickname' => 'extension']);
          
    }


    function register()
    {     
        // require 'functions.php'; 

        $this->commands([
            Console\ComposerMakeCommand::class,
            Console\ExtensionLinkCommand::class, 
        ]);
    }  

    public function mapAuthRoutes()
    { 
        app('router')->middleware(config('admin.panel.middleware', ['web', 'auth:admin']))
                     ->prefix(config('admin.panel.path_prefix', 'panel'))
                     ->namespace(__NAMESPACE__.'\Http\Controllers') 
                     ->group(__DIR__.DS.'routes.php') ; 
    }  
}
