<?php 
namespace Core\Plugin;

use Illuminate\Support\ServiceProvider; 
use Core\Contracts\Bootable;

class PluginServiceProvider extends ServiceProvider
{    
    protected $defer = true;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {     
        $this->app->booted(function($app) {  
            foreach(armin_plugins() as $plugin) {    
                if($plugin instanceof Bootable) {  
                    $plugin->boot(); 
                }
            } 
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('armin.repository.plugin', function() { 
            return new PluginRepository($this->app['files']);
        }); 

        // require __DIR__.'/functions.php'; loaded in composer

        $this->commands(Console\PluginMakeCommand::class); 
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'armin.repository.plugin'
        ];
    } 
}