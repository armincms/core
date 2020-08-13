<?php 
namespace Core\Crud\Concerns;
use Core\User\Concerns\Ownable;
use Core\Crud\Statuses;

trait HasSeo
{   
    private $translatable = true;

    public function seoFields($translatable = true, $attrs = [], $wrap_attrs = [])
    {   
        $this->translatable = $translatable;
        
        $this  
            ->field('text', 'seo[title]', $translatable, 'armin::title.title', [], null, $attrs, $wrap_attrs)
            ->field('textarea', 'seo[description]', $translatable, 'armin::title.description', [], null, $attrs, $wrap_attrs); 

         return $this;
    }   

    public function transformSeo($seo)
    {   
        if(! $this->translatable) {
            return $seo;
        }

        $converted = []; 

        foreach ($seo as $type => $meta) {
            foreach ($meta as $key => $value) {
                data_set($converted, "{$key}.{$type}", $value); 
            }  
        }

        return $converted; 
    } 
}