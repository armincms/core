<?php
namespace Core\User;

use Illuminate\Support\ServiceProvider;   
use Illuminate\Database\Schema\Blueprint; 
use Config; 

class UserServiceProvider extends ServiceProvider
{

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {     
        $this->loadViewsFrom(__DIR__.'/resources/views', 'user-management');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'user-management');     
        $this->loadMigrationsFrom(__DIR__.'/database/migrations'); 
        $this->map(); 
        $this->setConfigurations();

        require __DIR__.'/functions.php'; 
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {         
        $this->registerMacros(); 
    } 

    public function setConfigurations()
    {

        Config::set('imager.schemas.avatar', [
            'group'       => 'user-management', // group of usage
            'name'        => 'avatar', // unique name
            'resize'      => 'crop', // resize type
            'width'       => 75,
            'height'      => 75,
            'position'    => 'top', // crop postiion anchor
            'upsize'      => false, // cutting type
            'compress'    => 50,
            'extension'   => 'jpg', // save extension
            'placeholder' => '/admin/rtl/img/user.png',
        ]); 

        Config::set('auth.providers.users.model', Models\User::class);
        Config::set('auth.providers.admins.model', Models\Admin::class);
        
        Config::set("filesystems.disks.user.driver", 'local'); 
        Config::set("filesystems.disks.user.root", upload_path('images/users')); 
        Config::set("filesystems.disks.user.url", upload_url('images/users')); 
        Config::set("filesystems.disks.user.visibility", 'public'); 

        Config::set("filesystems.disks.admin.driver", 'local'); 
        Config::set("filesystems.disks.admin.root", upload_path('images/admins')); 
        Config::set("filesystems.disks.admin.url", upload_url('images/admins')); 
        Config::set("filesystems.disks.admin.visibility", 'public');
        
    }

   public function registerMacros()
   { 
        Blueprint::macro('ownables', function($name = 'owner') {
            $this->nullableMorphs($name);
        });
        Blueprint::macro('dropOwnabels', function($name = 'owner') {
            $this->dropMorphs($name);
        });
   }
 
    public function map()
    { 
        $this
            ->app['router']
            ->namespace(__NAMESPACE__.'\Http\Controllers')
            ->prefix(config('admin.path', 'admin'))
            ->name('admin.')
            ->middleware('web')
            ->group(function($router) {
                $router->auth([
                    'register' => false, 'reset' => false, 'confirm' => false, 'verify' => false
                ]);
            });  
    }
}
