<?php 
namespace Core\Crud;
 

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request; 
use Core\Crud\Http\Resources\Collection;
use Core\Crud\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

abstract class ResourceApi extends BaseController 
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $with = []; 


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $query  = $this->model()->with($this->getEagerLoads());

        $this->applyOrders($query, $request->get('orders', []));
        $this->applyFilters($query, $request->get('filters', []));
 

        $resources  = $query->paginate($request->get('count', 10))->appends(
            $request->except('page')
        );

    	return $this->collection($resources);
    } 

    public function collection($resources)
    {
        return new Collection($resources);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $resource)
    { 
        presenting_resource($resource);

        return $this->resource($resource);
    }

    public function resource($resource)
    {
        return new Resource($resource);
    }

    protected function getEagerLoads()
    {
        return collect(request('eagerloads', []))->union((array) $this->with)->toArray();
    }

    protected function applyFilters($query, $filters = [])
    { 
        collect($filters)->each(function($value, $key) use ($query) {
            $this->applyFilter($query, $key, $value); 
        });   
    }

    public function applyFilter($query, $key, $value)
    {
        $callback = $this->getFilterCallback($key); 

        try {
            return call_user_func_array(
                [$this, $callback], compact('query', 'key', 'value')
            );
        } catch (\Exception $e) {
            return $query;
        } finally {
            return $query;
        }
    }

    private function applyDefaultFiltering($query,  $key, $value)
    {  
        return $query->where($query->qualifyColumn($key), $value);   
    }

    protected function hasFilter($type)
    {  
        return method_exists($this, $this->makeFilterCallback($type));
    }

    protected function getFilterCallback($type)
    { 
        $filter = $this->hasFilter($type) ? $type : 'default'; 

        return $this->makeFilterCallback($filter);
    }

    protected function makeFilterCallback($type)
    {
        $name = studly_case($type);

        return "apply{$name}Filtering";
    } 

    protected function applyOrders($query, $orders = [])
    { 
        collect($orders)->each(function($order) use ($query) {
            $this->applyOrder(
                $query, array_get($order, 'column'), array_get($order, 'dir') === 'desc'? 'desc':'asc'
            ); 
        });    
    }

    public function applyOrder($query, $key, $dir = 'asc')
    {  
        if($this->hasCustomOrdering($key)) { 
            return call_user_func_array(
                [$this, $this->makeOrderingCallback($key)], compact('query', 'dir')
            );
        } 

        return $this->applyDefaultOrdering($query, $key, $dir);
    }

    private function applyDefaultOrdering($query, $key, $dir)
    {  
        return $query->orderBy($query->qualifyColumn($key), $dir)->toSql();   
    } 

    protected function hasCustomOrdering($type)
    {  
        return method_exists($this, $this->makeOrderingCallback($type));
    }

    protected function makeOrderingCallback($type)
    {
        $name = studly_case($type);

        return "apply{$name}Ordering";
    } 

    abstract public function model();
 
} 