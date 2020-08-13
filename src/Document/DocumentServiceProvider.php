<?php 
namespace Core\Document;
 
use Illuminate\Foundation\AliasLoader;   
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;

class DocumentServiceProvider extends ServiceProvider
{  
    protected $defer = true;

	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        $this->registerDocumentEngine(); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->app->singleton('document.html.meta', function($app) {
            return new HtmlMetaBuilder;
        });

        $this->app->singleton('document.html.plugin', function($app) {
            return new PluginManager;
        });

        $this->app->bind('document', function($app) {
            return new DocumentManager($app);
        });  

        \Helper::registerAlias([
            'HtmlMeta'  => Facades\HtmlMeta::class,
            'HtmlPlugin'=> Facades\HtmlPlugin::class
        ]);
    } 

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerDocumentEngine()
    {   
        $this->app->view->getEngineResolver()->register('document', function () {  
            return new HtmlDocument($this->app['blade.compiler']);
        });

        $this->app->view->addExtension('document.blade.php', 'document'); 
    } 

    public function provides()
    {
        return [
            'document', 'document.html.meta', 'document.html.plugin'
        ];
        
    }
}