<?php 
namespace Core\HttpSite; 

use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Arr; 
use Core\HttpSite\Http\Controllers\WebsiteController;  
use Core\HttpSite\Http\Controllers\ComponentController;  
use Core\HttpSite\Http\Controllers\FallbackController;  
use Core\HttpSite\Http\Middlewares\Redirector;  
use Core\HttpSite\Http\Controllers\SiteController;  
use Core\HttpSite\Contracts\SiteRequest; 
use Illuminate\Foundation\AliasLoader;
use Laravel\Nova\Nova; 
use Gate;
use Config;
use Site;

class HttpSiteServiceProvider extends ServiceProvider
{ 
    protected $excepts = [  
        'api',
        'api/*',
        'nova', 
        'nova/*', 
        'dashboard', 
        'dashboard/*', 
        'nova-api', 
        'nova-api/*', 
        'nova-vendor', 
        'nova-vendor/*', 
        'panel', 
        'panel/*',
        'laravel-filemanager',
    ];

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
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'http-site');      
        // $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'http-site'); 
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations'); 
        $this->mergeConfigFrom(__DIR__.'/config.php', 'http-site'); 
        $this->map();  
        $this->moduleConfiguration();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {     
        $this->app->when([ComponentController::class, WebsiteController::class, Redirector::class])
            ->needs(Contracts\SiteRequest::class)
            ->give(function() { 
                return app(\Core\HttpSite\Http\Requests\SiteRequest::class);
            });

        $this->app->when([FallbackController::class])
            ->needs(Contracts\SiteRequest::class)
            ->give(function() { 
                return app(\Core\HttpSite\Http\Requests\FallbackRequest::class);
            });

        $this->app->bind(
            \Core\Template\Contracts\Repository::class, Repository\TemplateRepository::class
        );  

        $this->app->singleton('armin.site', function($app) {
            return $app['site'];
        });

        $this->app->singleton('site', function($app) { 
            return new SiteManager(); 
        });

        \Helper::registerAlias('Site', Facades\Site::class);

 
        require __DIR__.DS.'functions.php';


        // $this->app['Illuminate\Contracts\Http\Kernel']
        //         ->pushMiddleware(Http\Middlewares\Redirector::class); 
    }

    public function map()
    {  
        $this->app->booted(function() { 
            $this->registerWebRoutes();
        });  
    } 

    public function registerWebRoutes()
    {     
        $this->excepts[] = trim(Nova::path(), '/');
        $this->excepts[] = trim(Nova::path(), '/').'/*'; 

        if($this->app->runningInConsole() || request()->is($this->excepts)) { 
            return; 
        }   
 
        $this->app->booted(function($app) {  
            $manager = tap(app('site'), function($manager) {
                $manager->push('home', function($site) { 
                    $site->fallback()->home();
                });
            });

            $sites = $manager->collect()->sortByDesc(function($site) {
                // push home site into last
                return strlen($site->directory()) + ($site->name() === 'home' ?: time());
            }); 

            $sites->each(function($site, $name) {     
                $site->domains()->each(function($domain) use ($site) { 
                    app('router') 
                        ->domain(UrlHelper::assertDefaultHost($domain)? '' : $domain)
                        ->prefix($site->directory())
                        ->middleware($site->middlewares()->toArray())
                        ->namespace(__NAMESPACE__.'\Http\Controllers')
                        ->name($site->name(). '.')
                        ->group(function($router) use ($site) { 
         
                            $router->get('/', "WebsiteController"); 
                             
                            $site->components()->each(function($component, $key) use ($router, $site) { 

                                // sub routes fallback of site component
                                $globPattern = preg_match_all('/\{([^}]+)\}/', $component->route(), $matches);
                                $key = array_pop($matches[1]);

                                $router
                                    ->get($component->route(), "ComponentController")
                                    ->where($component->wheres())
                                    ->where($key ?: '*', '.*')
                                    ->name($component->name()); 
                            });  

                            // pass alll related route of site
                            if(! empty($site->directory()) && $site->name() !== 'home') {
                                $router->get('{any}', "ComponentController")->where('any', '.*');  
                            } 
                        });  
                });
            });   

            app('router')->fallback("Core\HttpSite\Http\Controllers\FallbackController");
            // dd(app('router')->getRoutes()->get('GET')); 
        });
 
    }  

    public function moduleConfiguration()
    {
        Config::set("module.locatables.site", [
            'title' => 'http-site::title.sites',
            'name' => 'site',
            'items' => app('site')->collect()->map(function($site) {
                return [
                    'title' => $site->title()?: $site->name(),
                    'id'    => $site->name(),
                ];
            })->toArray(),
        ]); 
    }
     
}

