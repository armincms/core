<?php 
namespace Core\Form;

use Form;


class BSFormComponentRegisterar 
{
    
	public function register()
	{
		$this->registerSelectFields();
		$this->registerInputFields();
		
	}

    public function registerSelectFields()
    {
        
        Form::component('bsSelect', 'form::components.bs.select', [
            'name',
            'label'     => null, 
            'values'    => [],
            'selected'  => [],
            'attributes'=> [], 
            'options_attributes'     => [],
            'optiongroups_attributes'=> [],
            'wrapper_attributes'     => [],
            'help' => null,
        ]);    
    }

    public function registerInputFields()
    { 
        Form::component('bsInput', 'form::components.bs.input', [
            'name',
            'label'     	=> null, 
            'input_label' 	=> null, 
            'value'    		=> null, 
            'attributes'	=> [],  
            'wrapper_attributes'     => [],
            'help' => null,
            'type' => 'text',
        ]);     

    }

    public function registerTextareas()
    {

        Form::component('bsTextarea', 'form::components.bs.textarea', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null, 
        ]);

        Form::component('bsEditor', 'form::components.bs.editor', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null, 
        ]); 
    }

    public function registerInputs()
    {
        Form::component('bsInput', 'form::components.bs.input', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null,
            'addon' => [],
            'type' => 'text'
        ]); 

        Form::component('bsText', 'form::components.bs.input', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null,
            'addon' => [],
            'type' => 'text'
        ]); 
        Form::component('bsCheckbox', 'form::components.bs.checkbox', [ 
            'name',
            'label' => null,
            'value' => null,   
            'checked' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null, 
        ]);
        Form::component('bsPassword', 'form::components.bs.password', [ 
            'name',
            'label' => null,  
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null,
            'type' => 'password'
        ]);
        Form::macro('bsPhone', function($name, $label= null, $value = null) {
            $args = func_get_args(); 

            $args[1] = $label;
            $args[2] = $value;
            $args[3] = array_merge(['pattern' => '[0-9]{7,12}'], (array)array_get($args,3)); 

            return call_user_func_array([Form::class, 'bsText'], $args);
        });
        Form::component('bsIcheck', 'form::components.bs.icheck', [ 
            'name',
            'label' => null,  
            'radios' => [
                0 => [
                    'value' => 0,
                    'label' => 'armin::title.deactive',
                    'attributes' => [],
                    'label_attributes' => [],
                ],
                1 => [
                    'value' => 1,
                    'label' => 'armin::title.active',
                    'attributes' => [],
                    'label_attributes' => [],
                ], 
            ],
            'checked'   => 0,  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help'  => null, 
        ]);
    }
    public function registerFileInputs()
    {
        Form::component('bsFile', 'form::components.bs.file', [ 
            'name',
            'label' => null, 
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null,
            'addon' => [] 
        ]);

        Form::component('bsImageUploader', 'form::components.bs.image-uploader', [ 
            'name',
            'label' => null, 
            'images'=> [],
            'previw'=> true,
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'help' => null 
        ]);  
    }
}