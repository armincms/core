<?php 
namespace Core\Crud\Concerns;

trait HasSelectable
{ 
	public function selectable($name, $label = null, array $options, array $selected = [], array $disabled = [], array $config = [], array $attributes = [], array $wrapper_attributes = [])
    { 
        $this
            ->element('hidden', $name, '')
            ->raw(
                view('admin-crud::components.selectable', [
                    'name'              => $this->appendPrefix($name), 
                    'label'             => $label, 
                    'options'           => $options, 
                    'selected'          => $selected, 
                    'disabled'          => $disabled, 
                    'config'            => $config, 
                    'attributes'        => $attributes, 
                    'wrapper_attributes'=> $wrapper_attributes,
                ])->render()
            ); 

        return $this;
    }
	
}