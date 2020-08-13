<?php

namespace Core\Installer;

use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Facades\Event;

class InstallerServiceProvider extends ServiceProvider
{ 

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {  
        // \Site::push('installer', function($site) {
        //     $site
        //         ->directory('install')
        //         ->title('ArminCms | Installler')
        //         ->pushComponent(new Components\Configuration)
        //         ->pushComponent(new Components\Dbase)
        //         ->pushComponent(new Components\Migrate);
        // }); 

        $this->loadViewsFrom(__DIR__.'/resources/views', 'installer'); 

        if (! $this->installed()) {   
            $this->app->bind('middleware.disable', function () {
                return true;
            }); 

            Event::listen('Illuminate\Routing\Events\RouteMatched', function($e) {  
                if(! $e->request->is('installer/*')) { 
                    exit(redirect('/installer/configuration'));
                } 
            }); 

            app('router')->group([
                'prefix' => 'installer', 
                'middleware' => 'web',
                'namespace' =>  __NAMESPACE__.'\Http\Controllers',
                'as'  => 'installer.'
            ], function($router) { 
                $router->get('configuration','Installer@configuration');
                $router->post('configuration','Installer@configured')->name('configuration');
                $router->get('migrate', 'Installer@migrate');
                $router->post('migrate', 'Installer@migrated')->name('migrate');
                $router->get('dbseed', 'Installer@seed');
                $router->post('dbseed', 'Installer@dbseed')->name('dbseed');   
                $router->get('login', 'Installer@showLoginForm');
                $router->post('login', 'Installer@login')->name('login'); 
            });
        }  
        // dd(app('router')->getRoutes());
        // $this->app->booted(function($app) { 
        //     $app['document.html.plugin']->register(new InstalllerPlugin($app)); 
        // });

    }  

    public function installed()
    {
        return file_exists(__DIR__.'/install');
    }
}
