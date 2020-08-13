<?php 
namespace Core\Menu;

use Illuminate\Support\ServiceProvider;    

class MenuServiceProvider extends ServiceProvider
{    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @deprecated Implement the \Illuminate\Contracts\Support\DeferrableProvider interface instead. Will be removed in Laravel 5.9.
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'menu');      
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'menu'); 
        $this->loadMigrationsFrom(__DIR__.'/database/migrations'); 
        // $this->mergeConfigFrom(__DIR__.'/config.php', 'menu');  
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    { 
        \ArminResource::register('menu', Http\Controllers\MenuController::class);

        $this->app->bind(
            'armin.repository.menu', Repository\MenuRepository::class
        );  
    }


    public function provides()
    {   
        return [
            'armin.repository.menu'
        ];
    } 
      
}