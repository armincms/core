<?php 
namespace Core\Armin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader; 
use Illuminate\Support\Facades\Config;  
use Blade;

class ArminServiceProvider extends ServiceProvider
{ 

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {     
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'armin');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'armin'); 
        $this->registerBladeDirectives();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        // directory separator constatnt
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        
        // $this->loadConfigs();

        defined('ABSPATH') || define('ABSPATH', dirname(__FILE__));  
 
        $this->bindHelperClass(); 
        $this->registerProviders(); 
        $this->registerCommands(); 
        $this->registerMiddlewares();

        $this->app->booted(function() {
            $this->registerStorages();
        });
        
    } 

    public function registerProviders()
    {  
        // $this->app->register(\Core\Installer\InstallerServiceProvider::class);
        $this->app->register(\Jenssegers\Agent\AgentServiceProvider::class); 
        $this->app->register(\Core\Plugin\PluginServiceProvider::class);
        $this->app->register(\Core\Crud\CrudServiceProvider::class);
        $this->app->register(\Core\HttpSite\HttpSiteServiceProvider::class);
        $this->app->register(\Core\Option\OptionServiceProvider::class);
        $this->app->register(\Core\Document\DocumentServiceProvider::class);
        // $this->app->register(\Core\Documentation\DocumentationServiceProvider::class);
        $this->app->register(\Core\App\AppServiceProvider::class);
        // $this->app->register(\Core\FileManager\FileManagerServiceProvider::class);
        $this->app->register(\Core\Menu\MenuServiceProvider::class);
        // $this->app->register(\Core\Setting\SettingServiceProvider::class);
        $this->app->register(\Core\Language\LanguageServiceProvider::class);
        $this->app->register(\Core\Extension\ExtensionServiceProvider::class); 
        $this->app->register(\Core\Dashboard\DashboardServiceProvider::class);    
        $this->app->register(\Core\Module\ModuleServiceProvider::class);    
        $this->app->register(\Core\Form\FormServiceProvider::class);
        $this->app->register(\Core\Imager\ImagerServiceProvider::class); 
        // $this->app->register(\Core\Log\LogServiceProvider::class);
        // $this->app->register(\App\Providers\RouteServiceProvider::class);
        $this->app->register(\Core\User\UserServiceProvider::class);   
        $this->app->register(\Core\Component\ComponentServiceProvider::class);
        $this->app->register(\Core\Template\TemplateServiceProvider::class);
        $this->app->register(\Core\Layout\LayoutServiceProvider::class);     	
    } 

    public function registerCommands()
    { 
    	if($this->app->runningInConsole()) { 
	        $this->commands([
	            Console\CmsMakeCommand::class
	        ]);
    	}
    }

    public function bindHelperClass()
    { 
        tap(new Helper, function($helper) {
            $helper->registerAlias([
            	'Helper' => Facades\HelperFacade::class, 
            ]);

        // $this->app->singleton('_register', 'Armin\Core\RegisterClass');
        // $loader->alias('_register', 'Armin\Facades\RegisterFacade');
        // $loader->alias('Helper', 'Armin\Facades\HelperFacade');     
        // $loader->alias('Agent', 'Jenssegers\Agent\Facades\Agent');   
        // $loader->alias('Gateway', 'Larabookir\Gateway\Gateway')

            $this->app->singleton('helper', function() use ($helper) {
                return $helper;
            });
        });
    }  

    public function registerMiddlewares()
    {
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel']; 

        // $kernel->prependMiddleware(Http\Middlewares\WordWideWebRedirector::class);  
        $kernel->pushMiddleware(Http\Middlewares\CheckForMaintenanceMode::class); 
        // $kernel->pushMiddleware(Http\Middlewares\Configuration::class); 
    } 

    public function registerStorages()
    { 
        Config::set("filesystems.disks.armin.public.driver", 'local'); 
        Config::set("filesystems.disks.armin.public.root",  public_path('/')); 
        Config::set("filesystems.disks.armin.public.url", url('/', [], request()->secure())); 
        Config::set("filesystems.disks.armin.public.visibility", 'public');

        Config::set("filesystems.disks.armin.file.driver", 'local'); 
        Config::set("filesystems.disks.armin.file.root",  upload_path()); 
        Config::set("filesystems.disks.armin.file.url", upload_url()); 
        Config::set("filesystems.disks.armin.file.visibility", 'public');  
    }

    protected function registerBladeDirectives()
    {
    	// for define variables	
        Blade::directive('var', function ($expression) { 
            return '<?php ' .rtrim($expression). '; ?>';
        });
        
		// for encode json
        Blade::directive('json', function ($expression) { 
            return "<?php echo json_encode($expression); ?>";
        });  

        // for datetime display
        Blade::directive('datetime', function ($expression) {
        	if(empty($expression)) {
        		$expression = 'null';
        	}
             
            return '<?php echo Helper::format(' .$expression. ');?>'; 
        });

        // for date display
        Blade::directive('date', function ($expression) {
        	if(empty($expression)) {
        		$expression = 'null';
        	}

            return '<?php echo Helper::format(' .$expression. ', "Y-m-d");?>'; 
        });

        // for time display
        Blade::directive('time', function ($expression) {
	        if(empty($expression)) {
	        	$expression = 'null';
	        } 

            return '<?php echo Helper::format(' .$expression. ', "H:i:s");?>'; 
        });

        // for datetime display in wanted format
        Blade::directive('format', function ($expression) { 
            $variables = explode(',', $expression);   

            return '<?php echo Helper::format(' .$variables[0].  ',' .$variables[1]. ');?>'; 
        });
    }

}
