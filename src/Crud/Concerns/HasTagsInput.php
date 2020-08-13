<?php 
namespace Core\Crud\Concerns;

trait HasTagsInput
{ 
	public function inputTags($name, $label = null, array $selected = [], array $attributes = [], array $wrapper_attributes = [])
    { 
        $this
            ->element('hidden', $name, null, ['disabled'])
            ->raw(
                view('admin-crud::components.tags-input', [
                    'name'              => $name, 
                    'label'             => $label,  
                    'selected'          => $selected, 
                    'attributes'        => $attributes, 
                    'wrapper_attributes'=> $wrapper_attributes,
                ])->render()
            ); 

        return $this;
    }
	
}