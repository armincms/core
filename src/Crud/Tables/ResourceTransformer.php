<?php
namespace Core\Crud\Tables;

use League\Fractal\TransformerAbstract; 
use Illuminate\Database\Eloquent\Model;   
use Core\Crud\Contracts\PublicatableResource;
use Core\Crud\Statuses;

class ResourceTransformer extends TransformerAbstract
{ 
    use HasAction;

    protected $resource;
    public $currentItem;

    public function __construct($resource)
    {
    	$this->resource = $resource;  
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */
    public function transform(Model $resource)
    {   
        $this->currentItem = $resource;

    	$action = $this->addActions(
            $this->appendResourceToActions($this->getActions($resource), $resource)
        ); 

        $publication = $this->getPublication($resource); 

        return $this->toArray($resource)+ compact('action', 'publication'); 
    } 

    public function toArray($resource)
    {
        return $resource->toArray();
    }

    protected function appendResourceToActions($actions, $resource)
    { 
        return collect($actions)->map(function($data) use ($resource) {
            $data = is_array($data) ? $data : ['href' => $data];
            $data['resource'] = $resource; 

            return $data;
        })->toArray(); 
    }

    public function getActions($resource)
    {   
        if($this->isTrashed($resource)) {
            return $this->getTrashedActions($resource); 
        } 

        return $this->getDefaultActions($resource);
    } 

    protected function isTrashed($resource)
    {
        return $this->isRecyclable($resource) && $resource->trashed();
    }

    public function isRecyclable($resource)
    {
        return method_exists($resource, 'trashed');
    } 

    protected function getTrashedActions($resource)
    {  
        return array_merge([
            'restore' => $this->route('restore', $resource)
        ], $this->getDestroyAction($resource));
    }

    public function route($action, $resource)
    {
        $name = $this->resource->name();

        return route("{$name}.{$action}", $this->resource->routeParameters($action, $resource));
    }

    protected function getDestroyAction($resource)
    {
        if(! $this->isRecyclable($resource)) {
            return ['delete' => $this->route('destroy', $resource)]; 
        }
        
        if($resource->trashed()) { 
            return ['delete' => $this->route('delete', $resource)]; 
        }

        return ['destroy' => $this->route('destroy', $resource)];  
    }

    public function getDefaultActions($resource)
    {   
        $actions['edit'] = $this->route('edit', $resource);

        return array_merge($actions, $this->getDestroyAction($resource)); 
    }

    public function getPublication($resource)
    { 
        if($this->resource instanceof \Core\Crud\Contracts\PublicatableResource) {
            return view('admin-crud::include.publication-dropdown', [ 
                'resource'  => $resource,  
                'availables'=> $this->resource->getAvailableStatuses(),
                'active'    => array_get($resource, $this->resource->getStatusColumn()),
                'href'      => $this->route('publication', $resource),

            ])->render();
        }

        return null; 
    }
}