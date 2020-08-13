<?php 
namespace Core\Module\Tables;

use Core\Crud\Tables\ResourceTransformer;
use Illuminate\Database\Eloquent\Model;  

class ModuleTransformer extends ResourceTransformer
{  


    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */
    public function transform(Model $resource)
    {   
        $data = parent::transform($resource);  

        $data['title'] = "{$data['title']}<small class=clearfix>{$resource->description}</small>";

        return $data;
    }
}
