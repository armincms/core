<?php 
namespace Core\FileManager;

use Illuminate\Support\ServiceProvider;  
use Config;  

class FileManagerServiceProvider extends ServiceProvider
{    

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {   
        $this->loadViewsFrom(__DIR__.'/resources/views', 'file-manager');
        $this->map();  

        $this->registerStorages(); 
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    { 
    }


    public function map()
    {  
        if(! $this->app->routesAreCached()) { 
            $this->mapAuthRoutes();  
        }
    }
     

    public function mapAuthRoutes()
    { 
        app('router')->middleware(config('admin.panel.middleware', ['web', 'auth:admin']))
                     ->prefix(config('admin.panel.path_prefix', 'panel'))
                     ->namespace(__NAMESPACE__.'\Http\Controllers') 
                     ->group(function($router) { 
                        $router->apiResource('file-manager', 'FileManagerController', [
                            'only' => ['index', 'destroy', 'store']
                        ]);  
                        
                        \Menu::get('bigMenu')->add('titles.files', ['route' => 'file-manager.index']);
                     });  
    } 

    public function registerStorages()
    { 
        foreach (['image', 'video', 'audio'] as $media) {
            $path = str_plural($media);

            Config::set("filesystems.disks.armin.{$media}.driver", 'local'); 
            Config::set("filesystems.disks.armin.{$media}.root",  upload_path($path)); 
            Config::set("filesystems.disks.armin.{$media}.url", upload_url($path)); 
            Config::set("filesystems.disks.armin.{$media}.visibility", 'public'); 
        }  
    }

}