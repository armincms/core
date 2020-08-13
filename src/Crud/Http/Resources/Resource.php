<?php 
namespace Core\Crud\Http\Resources;

use Illuminate\Http\Resources\Json\Resource as BaseResource; 
use Core\Language\Contracts\Multilingual;

class Resource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
        $data = parent::toArray($request);

        if($this->resource instanceof Multilingual) {
            return $this->mergeByTranslation($data, $request);
        }   

        return $data;
    }

    public function mergeByTranslation($data, $request)
    {
        foreach ($this->translates->first()->toArray() as $key => $value) {
            if(! isset($data[$key])) {
                $data[$key] = $this->resource->forceTrans($key);
            } 
        }

        $data['url'] = $request->url(). "/{$this->resource->id}";

        unset($data['translates']);

        return $data;
    }
}
