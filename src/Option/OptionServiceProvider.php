<?php 
namespace Core\Option;

use Illuminate\Support\ServiceProvider; 

class OptionServiceProvider extends ServiceProvider
{    

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {    
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');   

        // require __DIR__.'/helpers.php';    
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {  
    } 
}