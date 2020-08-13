<?php 
namespace Core\Crud;
 

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request; 
use Yajra\DataTables\Html\Builder;
use Core\Crud\Contracts\Compact;
use Core\Crud\Policies\ResourcePolicy;

use DataTables;
use Gate;

abstract class Resource extends BaseController 
{
    use DispatchesJobs, ValidatesRequests, AuthorizesRequests {
        authorize as parentAuthorize;
    }

    protected $with = [];
    protected $withCount = [];
    protected $editView     = 'admin-crud::edit';
    protected $indexView    = 'admin-crud::index';
    protected $tableView    = 'admin-crud::datatables.html-table';
    protected $compactView  = 'admin-crud::compact-edit'; 
    protected $orderedColumns = [];  
    protected $editingResource;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $builder)
    {  
        $this->authorize('viewAny');

    	if (request()->ajax()) {
    		return $this->getAjaxTable();
	    }

	    $table = $this->getTable($builder); 

        if($this instanceof Contracts\PublicatableResource) {  
            $table = $this->addPublicationColumn($table);
        }  

        $with = [
            'name' => $this->name(),
            'title' => $this->title(),
        ];

        if($this instanceof Compact) {
            $with['form']   = optional($this->form())->setModel($this->editingResource);
            $with['actions']= $this->getActions();
            $this->indexView = $this->compactView;
        }

        return view($this->indexView)->nest(
            'table', $this->tableView, compact('table')
        )->with($with)->withRouteParameters(
            (array) $this->routeParameters(
                $this->editingResource? 'edit' : 'create', $this->editingResource
            )
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create');

        $this->event('creating', $this->model());

        if($this instanceof Compact) {
            return $this->index(request(), app(Builder::class));
        }

        return view($this->editView)
        				->withForm($this->form())
        				->withName($this->name())
                        ->withTitle($this->title())
                        ->withActions($this->getActions())
                        ->withRouteParameters((array) $this->routeParameters('create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create');

        $resource = $this->form()->save(function($data,$form) {
            $general    = $data->pull('general'); 
            $relations  = $data->get('relations');

            $admin = \Auth::guard('admin')->user();
 
            $resource = $this->model()->forceFill($general->toArray()); 

            $this->event('creating', $resource);  

            $resource->save(); 

            $this->syncRelations($relations, $resource); 

            $this->event('created', $resource);   

            return $resource;
        }); 

        $this->checkOwner($resource);

        return $this->redirect($request, $resource);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $resource)
    {
        $this->authorize('update', $resource);

        $this->event('updating', $resource->load($this->with));  

        if($this instanceof Compact) {
            $this->editingResource = $resource;

            return $this->index($request, app(Builder::class));
        }

        return view($this->editView)
                        ->withForm($this->form()->setModel($resource))
                        ->withName($this->name())
                        ->withTitle($this->title())
                        ->withActions($this->getActions())
                        ->withRouteParameters((array) $this->routeParameters('edit', $resource));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource)
    {
        $this->authorize('update', $resource);

        $resource->load($this->with);

        $this->form()->setModel($resource)->save(function($data,$form) use (&$resource){
            $general    = $data->pull('general'); 
            $relations  = $data->get('relations');

            $admin = \Auth::guard('admin')->user();
 
            $resource->forceFill($general->toArray());    

            $this->event('updating', $resource);  

            $resource->save(); 

            $this->syncRelations($relations, $resource);  

            $this->event('updated', $resource); 

            return $resource;
        }); 

        $this->checkOwner($resource);

        return $this->redirect($request, $resource); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($resource)
    {
        $this->authorize('delete', $resource);

        $this->event('destroying', $resource);  

        $resource->delete();

        $this->event('destroyed', $resource);  

        return $resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    { 
        $this->authorize('forceDelete', $resource = $this->model()->withTrashed()->find($id));

        $this->event('deleting', $resource); 

        $resource->forceDelete();

        $this->event('deleted', $resource);

        return $resource;
    }

    /**
     * Restore the specified removed resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {  
        $this->authorize('restore', $resource = $this->model()->withTrashed()->find($id));

        $this->event('restoring', $resource); 

        $resource->restore();

        $this->event('restored', $resource); 

        return $resource;
    }

    /**
     * Change publication of the specified removed resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function publication(Request $request, $resource)
    {  
        $this->authorize('publishing', $resource);

        $this->event('publishing', $resource); 
        
        $resource->update([
            $this->getStatusColumn() => $request->status
        ]);

        $this->event('published', $resource);  

        return $resource;
    }

    protected function deleteResource($resource)
    { 
        $this->authorize('delete', $resource);

        $this->event('deleting', $resource);  

        $resource->delete();

        $this->event('deleted', $resource);  
    } 
    
    protected function getTable($builder)
    {  
        return $builder->columns(
            collect($this->columns())->map(function($column) {
                return $this->getNormalizedDatatableColumn($column);
            })->toArray()
        )->addAction([
            'title' => armin_trans('admin-crud::title.actions'),
            'printable' => false,
            'class' => 'align-center',
            'width' => 0, 
        ])->addCheckbox([
            'role' => 'bulk-check',
            'render' => 'function() {return "<input role=bulk-item type=checkbox value=" +this.id+ ">";}',
                'class' => 'hide-on-mobile',
        ], true);
    }

    protected function getNormalizedDatatableColumn($column)
    {
        // foreach (['searchable','orderable','exportable','printable'] as $key) {
        //     if(! isset($column[$key])) {
        //         $column[$key] = false;
        //     }
        // } 

        return array_except($column, ['order_callback', 'search_callback']); 
    }

    protected function addPublicationColumn($table)
    {
        return $table->addPublication([
                        'class' => 'align-center vertical-center',
                        'width' => 100,
                        'name' => $this->getStatusColumn()
                    ], -1);
    }

    public function getAjaxTable()
    { 
    	$table = $this->getDataTable();

        $this->applyColumnFiltering($table);
        $this->applyColumnOrderings($table);

		return $table->setTransformer($this->getTableTransformer())
        ->setRowClass(function() {
            return 'blue-gradient'; 
        })->make(true); 
    }

    protected function applyColumnFiltering($table)
    {  
        foreach ($this->columns() as $key => $column) {   
            if((boolean) array_get($column, 'searchable', true)) {
                $this->applyColumnFilter($table, $column, $key);
            } 
        }    
    }

    protected function applyColumnFilter($table, $column, $name)
    { 
        $search = array_get($column, 'search_callback'); 
        $name = array_get($column, 'name', $name); 

        if(is_callable($search)) { 
            call_user_func_array([$table, 'filterColumn'], [$name, $search]); 
        }    
    }

    protected function applyColumnOrderings($table)
    {   
        $orderables = $table->request->orderableColumns();
        $columns = collect($this->columns())->mapWithKeys(function($column, $key) {
            return [array_get($column, 'name', $key) => $column];
        });

        return $table->order(function($q) use ($table, $orderables, $columns) {  
            foreach ($orderables as $orderable) {
                $name = $table->request->input("columns.{$orderable['column']}.name");
                $direction = $orderable['direction']; 
                $column = $columns->get($name);

                if($order = array_get($column, 'order_callback')) {
                    $order($q, $direction, $column);
                } else {
                    $this->defaultColumnOrdering($q, $name, $direction);
                }  
            } 

            if(method_exists($q, 'latest')) {
                $q->latest($q->qualifyColumn('id')); 
            } 
        });  
    }

    public function defaultColumnOrdering($q, $column, $direction)
    {
        return $q->orderBy($q->qualifyColumn($column), $direction);
    }

    public function getDataTable()
    {  
        $model = $this->model(); 

        if($this->checkSoftDeletesOnModel($model)) {
            $model = $model->withTrashed(); 
        } 

    	return DataTables::of($model->with($this->with)->withCount($this->withCount)); 
    } 

    protected function checkSoftDeletesOnModel($model)
    {
        return in_array(
            'Illuminate\Database\Eloquent\SoftDeletes', class_uses($model)
        );
    }

    protected function getTableTransformer()
    {
        return new Tables\ResourceTransformer($this);
    }

    public function checkOwner($resource)
    { 
        if($resource instanceof \Core\User\Concerns\Ownable) {
            $resource->load('owner');

            if(is_null($resource->owner)) {
                $resource->owner()->associate(request()->user());
            } 
        } 
    }

    public function redirect($request, $resource=null, $messages = [])
    {
        if($request->ajax()) {
            return $resource;
        }
        
        $action = $request->get('_action'); 

        if(isset($resource) && $action == 'save') {
            return redirect()
                    ->route(
                        $this->name(). '.edit', $this->routeParameters($action, $resource)
                    )->withSuccess(
                        $messages + [armin_trans('admin-crud::message.successfully_saved')]
                    );
        }
        if($action == 'save&new') {
            return redirect()
                    ->route(
                        $this->name(). '.create', $this->routeParameters($action)
                    )->withSuccess(
                        $messages + [armin_trans('admin-crud::message.successfully_saved')]
                    );
        }

        if($action != 'close') {
            $messages = array_push($messages, armin_trans('admin-crud::message.successfully_saved'));
        }

        return redirect()
                ->route(
                    $this->name(). '.index', $this->routeParameters($action, $resource)
                )->withSuccess($messages);
    }

    protected function getActions($action = null)
    {
        if($actions = $this->getCustomActions()) {
            return collect($actions);
        }

        return collect($this->defaultActions()); 
    }

    protected function getCustomActions()
    {
        return [];
    }

    protected function defaultActions()
    {
        $actions = [
            new Actions\Save(),
            new Actions\SaveAndNew(),
            new Actions\SaveAndClose(),
            new Actions\Close(
                route(
                    $this->name(). '.index', $this->routeParameters('index', $this->editingResource)
                )
            ),
        ];

        if($this instanceof Compact) {
            unset($actions[2]);
        }

        return $actions;
    }
    

    protected function syncRelations($relations, &$resource)
    { 
        $relations->each(function($data, $relation) use (&$resource) {
            $method = key($data);
            $values = reset($data);

            call_user_func_array(
                [$resource->$relation(), $method], [$values]
            );

            $resource->save(); 
        });  

        $resource->load($relations->keys()->toArray()); 
    }

    public function event($event, $resource) 
    {  
        $user = request()->user();

        $callbak = "{$event}_resource"; 

        if(method_exists($this, camel_case($callbak))) {
            $method = camel_case($callbak);

            $this->$method($resource, $user);
        }
        
        $callbak($resource, $user);
    }


    public function getApiController()
    {
        return;
    }

    public function routes($router)
    {
        return $router;
    }

    public function navigable()
    { 
        return property_exists($this, 'navigable') ? $this->navigable : true;
    } 

    public function navigationGroup()
    { 
        return $this->navigation ?? null;
    }

    public function routeParameters(string $action = null, $resource = null)
    {
        return collect([$resource])->filter()->all();
    }

    public function makePermission(string $ability)
    {
        return $this->slugName().".{$ability}";
    }

    public function slugName()
    {
        return snake_case($this->name(), '-');
    }

    public function resourcePermissions()
    {  
        Gate::resource($this->slugName(), ResourcePolicy::class); 

        return $this;
    }

    public function softDeletePermissions()
    {
        Gate::define($this->makePermission('restore'), ResourcePolicy::class.'@restore');
        Gate::define($this->makePermission('forceDelete'), ResourcePolicy::class.'@forceDelete');

        return $this;
    }

    public function publishingPermissions()
    {
        Gate::define($this->makePermission('publishing'), ResourcePolicy::class.'@publishing'); 

        return $this;
    } 

    public function authorize(string $permission, $resource = null)
    {
        return;
        if(\Gate::getPolicyFor($this->model())) {
            return $this->parentAuthorize($permission, $this->model());
        } 
        
    }


    abstract public function name();
    abstract public function title();
    abstract public function columns();  
    abstract public function model(); 
    abstract public function form(); 
} 
 