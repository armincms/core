<?php
namespace Core\Log; 

use Illuminate\Support\ServiceProvider;  
use Core\Log\Contracts\InstanceRepository;   
use Auth;

class LogServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__.DS.'permisions.php', 'laratrust_seeder.admin_role_structure.administrator');
        $this->loadViewsFrom(__DIR__.DS.'resources'.DS.'views', 'logger'); 
        $this->loadTranslationsFrom(__DIR__.DS.'resources'.DS.'lang', 'logger'); 
        $this->loadMigrationsFrom(__DIR__.DS.'database'.DS.'migrations');          
    }  

    function register()
    {    
    }  
}
