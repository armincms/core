<?php 
namespace Core\Component;

use Illuminate\Support\ServiceProvider;
use Core\Component\Repository\ComponentRepository;

class ComponentServiceProvider extends ServiceProvider
{     

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('armin.repository.component', ComponentRepository::class); 

        require __DIR__.'/functions.php'; 

        $this->commands(Console\ComponentMakeCommand::class); 
          
        foreach(components() as $componentName => $component) {  
            if(class_exists($component->get('provider')) || \Helper::isSuperAdmin()) {
                $provider = $this->app->register($component->get('provider'), [], true);    
                 
                $this->app->booted(function($app) use ($componentName) {  
                    $app['armin.repository.component']->install($componentName); 
                }); 
            } else {
                \Log::warning("Component {$componentName} unexpectedly removed"); 
                $this->app['armin.repository.component']->forget();
            }
        }  
    } 
}