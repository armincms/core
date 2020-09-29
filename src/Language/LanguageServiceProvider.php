<?php
namespace Core\Language; 

use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;  
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;  
use Core\Language\Repository\LanguageRepository;  
use LaravelLocalization;

class LanguageServiceProvider extends ServiceProvider
{     
    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {    
        $this->app['config']->set('laravellocalization', require __DIR__.'/laravellocalization.php');

        $this->app->booted(function() {  
            LaravelLocalization::setLocale(default_locale());  
        });   
    } 

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {     
        parent::register(); 

        // $this->app->singleton('translator', function ($app) {
        //     $loader = $app['translation.loader'];

        //     // When registering the translator component, we'll need to set the default
        //     // locale as well as the fallback locale. So, we'll grab the application
        //     // configuration so we can easily get both of these values from there.
        //     $locale = $app['config']['app.locale'];

        //     $trans = new Translator($loader, $locale);

        //     $trans->setFallback($app['config']['app.fallback_locale']);

        //     return $trans;
        // }); 


        $this->app->register(
            \Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class
        );   

        \Helper::registerAlias(
            'LaravelLocalization',  
            \Mcamara\LaravelLocalization\Facades\LaravelLocalization::class
        ); 

        $this->app->singleton('armincms.locales', function() {
            return [
                'fa' => [
                    'alias' => 'fa',
                    'title' => 'فارسی',
                    'name'  => 'fa',
                    'active'=> true,
                    'international' => 'Fa-Ir',
                    'direction'     => 'rtl',
                ]
            ];
        }); 
        
        require 'functions.php'; 
    }    
}
