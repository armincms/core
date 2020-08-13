<?php 
namespace Core\Crud;
  
use Illuminate\Http\Request;
 

abstract class MultilingualResource extends Resource 
{  
    protected $forceTranslate = 'title';

    public function __construct()
    {
        $this->with[] = 'translates';
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize($this->makePermission('create'));

        $resource = $this->form()->save(function($data) {
            $general    = $data->pull('general');
            $relations  = $data->get('relations');  
            $translates = $data->pull('translates')->filter(function($value) {
                return ! empty(array_get($value, $this->forceTranslate));
            });  

            if($translates->count()) { 
                $resource = $this->model()->forceFill($general->toArray());  

                $translates = $translates->map(function($data, $language) use($resource){
                    return $resource->translates()->firstOrNew(
                        $data + compact('language')
                    ); 
                });  

                creating_resource($resource, $admin = \Auth::guard('admin')->user()); 

                $resource->save();
                $resource->translates()->createMany($translates->toArray());
                $resource->load('translates');  

                $this->syncRelations($relations, $resource);

                created_resource($resource, $admin);

                return $resource;
            } 
        }); 

        $this->checkOwner($resource);

        return $this->redirect($request, $resource);
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
        $this->authorize($this->makePermission('update'));

        $resource->load($this->with);

        $this->form()->setModel($resource)->save(function($data,$form) use (&$resource){
            $general    = $data->pull('general');
            $translates = $data->pull('translates');  
            $relations  = $data->get('relations'); 
            $titles = $translates->pluck($this->forceTranslate)->filter(function($value) {
                return ! empty($value);
            }); 
            $admin = \Auth::guard('admin')->user();

            if($titles->count()) {
                $resource->forceFill($general->toArray()); 

                $translates->map(function($data, $language) use ($resource) {
                    $translate = $resource->translate($language);
                    $forced = trim(array_get($data, $this->forceTranslate));

                    if(isset($translate) && empty($forced)) { 
                        $translate->delete();
                    } else if(isset($translate)) {
                        $translate->forceFill($data + compact('language'));
                    } else if(! empty($forced)) {
                        $resource->translates()->create($data + compact('language'));
                    }   
                }); 

                updating_resource($resource, $admin);

                $resource->save();

                $resource->translates->map->update(); 

                $this->syncRelations($relations, $resource);

                updated_resource($resource->load('translates'), $admin); 

            } else { 
                return $resource=$this->deleteResource($resource);  
            }  

            return $resource->load('translates');
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
    public function delete($id)
    { 
        $this->authorize($this->makePermission('forceDelete'));

        deleting_resource(
            $resource = $this->model()->with('translates')->withTrashed()->find($id), 
            $admin = \Auth::guard('admin')->user()
        );  

        $resource->translates->map->delete();

        $resource->forceDelete();

        deleted_resource($resource, $admin);

        return $resource;
    }


    protected function getTable($builder)
    {  
        $table = parent::getTable($builder);

        if(language()->count() > 1) { 
            return  $table->addLanguage([
                        'class' => 'align-center vertical-center',
                        'width' => language()->count() * 24 + 40
                    ], -1);
        }

        return $table;
          
    } 


    public function getDataTable()
    {   
        $query = $this->getJoinedTranslationsQuery(
            $this->model()
        )->with($this->with)->withCount($this->withCount); 

        if($this->checkSoftDeletesOnModel($this->model())) {
            $query = $query->withTrashed(); 
        } 

        return \DataTables::of($query); 
    } 

    public function getJoinedTranslationsQuery($q)
    {
        $relation = $q->getModel()->translates();
        $relatedTable = $relation->getRelated()->getTable();  

        $q = $q->join(
            $relatedTable,
            $relation->getQualifiedParentKeyName(),
            '=',
            $relation->getQualifiedForeignKeyName()
        )/*->groupBy($q->qualifyColumn('id'))*/->select($q->qualifyColumn('*'));

        foreach($this->getJoinedColumns($relatedTable) as $sql) {
            if(! empty($sql)) {
                $q->addSelect($sql);
            }
        }

        return $q; 
    }

    public function getJoinedColumns($table)
    {
        $queries = [];

        foreach ($this->columns() as $key => $column) {
            if(array_get($column, 'multilingual')) {
                $name = array_get($column, 'name', $key);

                $queries[] = "{$table}.{$key} AS _{$name}";   
            } 
        } 
        
        return $queries;     
    }

    protected function applyColumnFilter($table, $column, $name)
    { 
        $multilingual = (boolean) array_get($column, 'multilingual', false);
        $searchable   = (boolean) array_get($column, 'searchable', true);
        $search = array_get($column, 'search_callback');
        $name   = array_get($column, 'name', $name); 
 

        if(! is_callable($search) && $multilingual) {   
            $search = function($q, $keyword) use ($name) { 
                $q->where(
                    $this->model()->translates()->qualifyColumn($name), 'LIKE', "%{$keyword}%"
                );
            }; 
        } 

        if($searchable && is_callable($search)) {  
            call_user_func_array([$table, 'filterColumn'], [$name, $search]); 
        } 
    }

    protected function applyColumnOrder($table, $column, $name)
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
                $order  = array_get($column, 'order_callback');

                if(is_callable($order)) {
                    $order($q, $direction, $column, $alias); 
                } else if(array_get($column, 'multilingual')) {  
                    $q->orderBy("_{$name}", $direction); 
                } else {
                    $q->orderBy($q->qualifyColumn($name), $direction);
                }   
            }  
            
            $q->latest('id');
        });  
    }  

    protected function getTableTransformer()
    {
        return new Tables\MultilingualResourceTransformer($this,  language());
    }
} 