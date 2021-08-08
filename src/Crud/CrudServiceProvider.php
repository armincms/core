<?php
namespace Core\Crud; 

use Illuminate\Support\ServiceProvider;  
use Illuminate\Foundation\AliasLoader;
use Yajra\DataTables\Html\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Form;
use Gate;

class CrudServiceProvider extends ServiceProvider
{   

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {     
    	$this->loadViewsFrom(__DIR__.'/resources/views', 'admin-crud');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'admin-crud');  

        $this->registerTranslatableFields();
        $this->registerResourceRoutes();  

        \Blade::directive('dropdown', function($args) {  
            return '<?php echo armin_dropdown(' . $args. '); ?>';
        });

        require __DIR__.'/functions.php'; 
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {  
        $this->app->make(HttpKernel::class)
                    ->pushMiddleware(Http\Middleware\ServeCore::class);
  
        
        $loader = AliasLoader::getInstance();

        $loader->alias('ArminResource', Facades\ArminResource::class);

        $this->app->singleton('armin.resource', function($app) {
            return new ResourceRegisterar($app);
        });

        \Config::set('datatables.script_template', 'dashboard::datatables.script'); 

        $this->registerQueryBuilders();

        $this->registerBluprints();

        $this->registerDatatableExtensions();
 

        $this->commands([
            Console\ResourceCrudMakeCommand::class,
            Console\ResourceModelMakeCommand::class,
            Console\ResourceModelTranslationMakeCommand::class,
            Console\ResourceTransformerMakeCommand::class,
            Console\ResourceFormMakeCommand::class,
        ]);
    } 

    public function registerQueryBuilders()
    {  
        QueryBuilder::macro('wherePublished', function() {
            $model = $this->getModel();

            return $this->where(
                $this->qualifyColumn($model->getStatusColumn()), $model->getPublishStatus()
            ); 
        });
    } 

    public function registerBluprints()
    { 
        Blueprint::macro('publishing', function($schedule = true, $default = 'draft') { 
            $this->string('status')->default(Statuses::key($default));

            if(true === $schedule) { 
                $this->timestamp('release_date')->nullable();
                $this->timestamp('finish_date')->nullable(); 
                $this->timestamp('archive_date')->nullable();
            }  
        });

        Blueprint::macro('statuses', 
            function($name = 'status', $schedule = true, $default = 'draft') { 
                $this->string($name)->default(Statuses::key($default));

                if(true === $schedule) { 
                    $this->timestamp('release_date')->nullable();
                    $this->timestamp('finish_date')->nullable(); 
                    $this->timestamp('archive_date')->nullable();
                }
            }
        ); 

        Blueprint::macro('seo', function() { 
            $this->text('seo')->nullable();  
        }); 

        Blueprint::macro('imageUploader', function($name = 'image') {  
            $this->string($name, 500)->nullable();  
        }); 
    }


    protected function registerResourceRoutes()
    { 
        \Event::listen(\Core\Crud\Events\CoreServing::class, function() { 
            $this->registerResourceCrud();
            // $this->registerResourceApiCrud();
            $this->registerResourceApi();  
        }); 
    }

    protected function registerResourceCrud()
    {
        $this->app['router']->middleware(
            config('admin.panel.middleware', ['web', 'auth:admin'])
        )
        ->prefix(config('admin.panel.path_prefix', 'panel').'/resources')
        ->group(function($router) {

            $menu = \Menu::get('bigMenu')->add(
                'admin-crud::title.resources', 'javascript::vois(0)'
            );

            app('armin.resource')->all()->each(function($data, $name) use ($menu, $router) { 
                $resource = $data['resource'];
                $data['controller'] = class_basename($resource);
                $namespace  = str_before(get_class($data['resource']), $data['controller']);  

                $router->namespace($namespace)->group(function($router) use ($data, $name, $resource) { 
                    $options    = collect($data['options']);  
                    $slug       = str_replace('-', '_', $name);

                    $router->group([
                        'prefix'=> "{$slug}",
                        'as'    => "{$resource->name()}."
                    ], function($router) use ($resource, $data, $name, $slug) {
                        
                        // $resource->resourcePermissions();
                        $resource->routes($router);

                        if(use_soft_deletes($resource->model())) {  
                            $resource->softDeletePermissions();

                            $router->post(
                                "{id}/restore", "{$data['controller']}@restore"
                            )->name("restore"); 
                            
                            $router->delete(
                                "{id}/delete", "{$data['controller']}@delete"
                            )->name("delete");  
                        }

                        if($resource instanceof Contracts\PublicatableResource){
                            $resource->publishingPermissions(); 

                            $router->put(
                                "{$name}/{{$slug}}/publication", "{$data['controller']}@publication"
                            )->name("publication");  
                        } 
                    });   
      
                    $routes = $router->resource($name, $data['controller']);  

                    if($only = array_get($options, 'only')) {
                        $routes->only($only);
                    }

                    if($except = array_get($options, 'except')) {
                        $routes->except($except);
                    } 

                    $routes->register();     

                    \Route::model($resource->name(), get_class($resource->model())); 

                    // if($parent = array_get($options, 'menu')) {
                    //     optional(admin_menus()->find($parent))->add($resource->title(), [
                    //         'route' => "{$name}.index" 
                    //     ]);  
                    // } else {
                    // }   
                });

                $this->resolveResourceNavigation($resource, $menu);
            }); 
        });  

        $this->app['router']->getRoutes()->refreshNameLookups();
        $this->app['router']->getRoutes()->refreshActionLookups();
       // dd(app('router')->getRoutes()->get('PUT'));
    }

    public function resolveResourceNavigation($resource, $nav)
    {
        if($resource->navigable()) {   
            if($group = $nav->builder->get($resource->navigationGroup())) {
                $nav = $group;  
            }

 
            $nav->add($resource->title(), [
                'route'     => $resource->name() .".index",
                'nickname'  => $resource->name()
            ]);
        } 
    }

    protected function registerResourceApi()
    { 
        $this->app['router']
            ->middleware('web'/*config('admin.api.middleware', ['web', 'auth:api'])*/)     
            ->prefix(config('admin.api.path_prefix', 'api/resources'))
            ->group(function($router) {  
                app('armin.resource')->all()->each(function($data,$name) use ($router){
                    $resource   = $data['resource'];
                    $options    = $data['options'];

                    if(method_exists($resource, 'hasApi') && $resource->hasApi()) {
                        $router->apiResource($name, $this->getApiController($resource), [
                            'names' => "api.{$name}",
                            'only'  => ['index', 'show']
                        ]);  
                    } 

                }); 
            });   
    }

    protected function getApiController($resource)
    {      
        if($controller = $resource->getApiController()) {
            return $controller;
        }   

        $resourceClass  = get_class($resource); 
        $className      = class_basename($resourceClass); 
        $controller     = str_replace($className, "Api\\{$className}", $resourceClass);

        if(class_exists($controller)) {
            return $controller;
        }
        
        throw new Exceptions\ResourceApiNotFoundException(
            'Api Controller Not Found For Resource ' . get_class($resource)
        ); 
    }

    protected function registerDatatableExtensions()
    { 
        if(! \Helper::isPanelPath()) return;
            
        Builder::macro('addLanguage', function(array $attributes=[], $position=false) {
            $attributes = array_merge([
                'defaultContent' => '',
                'data'           => 'translations',
                'name'           => 'translations',
                'title'          => armin_trans('titles.languages'),
                'render'         => '',
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '', 
            ], $attributes);

            $column = new \Yajra\DataTables\Html\Column($attributes);

            if ($position === true) {
                $this->collection->prepend($column);
            } elseif ($position === false || $position >= $this->collection->count()) {
                $this->collection->push($column);
            } else {
                $this->collection->splice($position, 0, [$column]);
            }

            return $this;
        });

        Builder::macro('addPublication', function(array $attributes=[], $position=false) {
            $attributes = array_merge([
                'defaultContent' => '', 
                'data'           => 'publication',
                'name'           => 'publication',
                'title'          => armin_trans('admin-crud::title.publication_status'),
                'render'         => '',
                'orderable'      => true,
                'searchable'     => false,
                'exportable'     => true,
                'printable'      => true,
                'footer'         => '', 
            ], $attributes);

            $column = new \Yajra\DataTables\Html\Column($attributes);

            if ($position === true) {
                $this->collection->prepend($column);
            } elseif ($position === false || $position >= $this->collection->count()) {
                $this->collection->push($column);
            } else {
                $this->collection->splice($position, 0, [$column]);
            } 

            return $this;
        });
    }



    function registerTranslatableFields()
    {      
        \Event::listen(\Core\Crud\Events\CoreServing::class, function() {
            $this->registerSelects();  
            $this->registerInputs();  
            $this->registerFileInpus();  
            $this->registerButtons();  
            $this->registerCheckables();  
            $this->registerUploaders();   
        });
    } 

    function registerFileInpus()
    {  
    }

    function registerInputs()
    { 
        Form::component('amdText', 'admin-crud::components.input', [ 
            'name',
            'translatable'       => true,
            'label'              => [],
            'input_label'        => [],
            'value'              => null,   
            'attributes'         => [],    
            'wrapper_attributes' => [], 
            'help'               => null,
            'type'               => 'text',
        ]); 
        Form::component('amdInputSelect', 'admin-crud::components.input-select', [ 
            'name',
            'translatable'       => true,
            'label'              => [],
            'input_label'        => [],
            'value'              => null, 
            'select'             => [],  
            'attributes'         => [],    
            'wrapper_attributes' => [], 
            'help'               => null,
            'type'               => 'text',
        ]); 
        Form::component('amdEmail', 'admin-crud::components.input', [ 
            'name',
            'translatable'       => true,
            'label'              => [],
            'input_label'        => [],
            'value'              => null,   
            'attributes'         => [],    
            'wrapper_attributes' => [], 
            'help'               => null,
            'type'               => 'email',
        ]); 
        Form::component('amdPassword', 'admin-crud::components.password', [ 
            'name',
            'translatable'       => true,
            'label'              => [],
            'input_label'        => [],  
            'attributes'         => [],    
            'wrapper_attributes' => [], 
            'help'               => null,
            'type'               => 'password',
        ]);  
        Form::component('amdTextarea', 'admin-crud::components.textarea', [ 
            'name', 
            'translatable'       => true,
            'label'              => [],
            'input_label'        => [],
            'value'              => null,   
            'attributes'         => [],    
            'wrapper_attributes' => [],
            'help'               => null, 
        ]); 
        Form::component('amdSwitch', 'admin-crud::components.switch', [ 
            'name',
            'translatable'      => true, 
            'label'             => [], 
            'on'                => 1,  
            'off'               => 0, 
            'checked'           => null, 
            'attributes'        => [],   
            'wrapper_attributes'=> [],
            'help'              => null,
        ]);    
    }
    function registerButtons()
    { 
        Form::component('amdButton', 'admin-crud::components.button', [
            'name',
            'label'             => null, 
            'icon'              => null, 
            'attributes'        => [],  
            'wrapper_attributes'=> [],
            'help'              => null,
        ]); 
        Form::component('amdSave', 'admin-crud::components.buttons.icon-button', [
            'name',
            'label'      => 'armin::action.save', 
            'icon'       => 'floppy', 
            'color'      => 'green', 
            'attributes' => [],   
            'help'       => null,
        ]); 
        Form::component('amdRadioButtons', 'admin-crud::components.buttons.radio-buttons', [
            'name',
            'label'              => 'armin::action.save',  
            'buttons'            => [],  
            'attributes'         => [],   
            'label_attributes'   => [],   
            'wrapper_attributes' => [],   
            'help'               => null,
        ]); 
        Form::component('amdGroupCheckable', 'admin-crud::components.group-buttons', [
            'name',  
            'translatable'       => true,
            'label'              => 'armin::action.save', 
            'radio'              => true,
            'buttons'            => [],  
            'attributes'         => [],           
            'wrapper_attributes' => [], 
            'help'               => null, 
        ]); 
    }

    function registerSelects()
    { 
        Form::component('amdSelect', 'admin-crud::components.select', [
            'name', 
            'translatable'           => false,
            'label'                  => [], 
            'values'                 => [],
            'selected'               => null,
            'attributes'             => [], 
            'options_attributes'     => [],
            'optiongroups_attributes'=> [],
            'wrapper_attributes'     => [],  
            'help'                   => null, 
        ]); 
    }

    public function registerCheckables()
    {
        Form::component('amdCheckable', 'admin-crud::components.checkable', [
            'name', 
            'translatable'      => false,
            'label'             => [], 
            'checkables'        => [],
            'checked'           => null,
            'radio'             => true,
            'attributes'        => [],  
            'wrapper_attributes'=> [],  
            'help'              => null, 
        ]); 

        Form::component('amdCheckbox', 'admin-crud::components.checkbox', [
            'name',
            'translatable'      => true, 
            'label'             => [], 
            'value'             => 1,   
            'checked'           => null, 
            'attributes'        => [],   
            'wrapper_attributes'=> [],
            'help'              => null,
        ]); 
    }

    public function registerUploaders()
    {
        Form::component('amdImageUploader', 'admin-crud::components.image-uploader', [
            'name',
            'translatable'      => false,
            'label'             => [], 
            'values'            => [], 
            'multiple'          => false,
            'attributes'        => [],  
            'wrapper_attributes'=> [],   
            'help'              => null, 
        ]);  
    }
}