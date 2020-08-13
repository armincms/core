<?php
namespace Core\Crud\Tables;
 
use Illuminate\Database\Eloquent\Model;  

class MultilingualResourceTransformer extends ResourceTransformer
{  
    protected $languages;
    protected $resource;

    public function __construct($resource, $languages)
    {
        $this->resource = $resource;
        $this->languages = $languages;  
    }    

    public function toArray($resource)
    {
        $data = $resource->toArray();
        $data['translations'] = $this->getTranslations($resource);

        foreach ($this->resource->columns() as $name => $column) {
            if(array_get($column, 'multilingual')) { 
                $key = array_get($column, 'data', $name);  

                data_set($data, $key, data_get($resource, $key));
            }
        }

        return $data;
    }

    public function getTranslations($resource)
    {
        return $resource->translates->map(function($translate) {
                return "<span class='lang-icon {$translate->language}-icon' style='display: inline-block;'></span>"; 
            })->implode(' ');
        
    }
}