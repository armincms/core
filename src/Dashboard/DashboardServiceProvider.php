<?php
namespace Core\Dashboard; 

use Illuminate\Support\ServiceProvider;   

class DashboardServiceProvider extends ServiceProvider
{   

    function __construct($app)
    {
        $this->app = $app;
        
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {    
    	$this->loadViewsFrom(__DIR__.'/resources/views', 'dashboard');
    	$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'dashboard');

        $this->setConfigs(); 

    	$this->app['router']
                    ->middleware(config('admin.panel.middleware', ['web', 'auth:admin'])) 
                    ->namespace(__NAMESPACE__.'\Http\Controllers') 
                    ->group(__DIR__.DS.'routes.php');  

        $this->makeAliasComponents();

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('admin/dashboard')
        ]);  
    }

    protected function setConfigs()
    {
        $configs = require __DIR__.'/config/admin.php';
        
        foreach ($configs as $key => $value) {
            if(is_array($value)) {
                $value = array_merge(
                    $value, (array) config("armin.admin.{$key}", [])
                );
            }

            \Config::set("armin.admin.{$key}", $value);
        }  
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->app->register(\Lavary\Menu\ServiceProvider::class);

        require __DIR__.'/functions.php';

        \Config::set(
            'datatables.script_template', 'dashboard::datatables.script'
        );
   
        \Config::set('laravel-menu.settings.bigmenu', [
            'active_class' => 'navigable-current',
            'restful'   => true
        ]); 

        $this->bigMenu = \Menu::makeOnce('bigMenu', function($menu) {}); 
    }

    function makeAliasComponents()
    {
        \Blade::component(
            'dashboard::components.breadcrumbs', 'arminBreadcrumbs'
        ); 
    }
}