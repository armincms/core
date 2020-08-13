<?php
namespace Core\HttpSite\Tables;

use Core\Crud\Tables\ResourceTransformer;
use Core\Dashboard\Table\Actionable; 
use Illuminate\Database\Eloquent\Model;  

class DomainTransformer extends ResourceTransformer
{  
    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */
    public function transform(Model $resource)
    {  
        $data = parent::Transform($resource);
        if($resource->root) {
            $data['name'] .= ' <b class=red>(' .armin_trans('http-site::title.root_domain'). ')</b>';
        } 

        return $data;
    }   
}