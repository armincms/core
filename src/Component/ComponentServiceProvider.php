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
            class_exists($component->get('provider')) && 
            $this->app->register($component->get('provider'), [], true); 
        }  
    } 
}