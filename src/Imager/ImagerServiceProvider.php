<?php
namespace Core\Imager; 

use Illuminate\Support\ServiceProvider;   


class ImagerServiceProvider extends ServiceProvider
{   
    protected $defer = true;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {      
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {    

        require __DIR__.'/functions.php'; 
        
        $this->mergeConfigFrom(__DIR__.'/config.php', 'imager'); 
        
        $this->app->register('Intervention\Image\ImageServiceProvider');

        $this->app->bind('armin.imager.schema', function() {
            return new SchemaRepository(config('imager.schemas'));
        });

        $this->app->bind('armin.imager', function($app) {
            return new Imager($app['armin.imager.schema']);
        });

        \Helper::registerAlias([
            'Image' => 'Intervention\Image\Facades\Image',
            'Imager'=> Facades\ImagerFacade::class
        ]); 
    }   

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['armin.imager', 'armin.imager.schema'];
    }
}