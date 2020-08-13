<?php 
namespace Core\Crud\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $collection = $this->resource->transform(function($item) {
            return new Resource($item);
        });
        
        return $this->resource->setCollection($collection)->toArray();
    }
}
