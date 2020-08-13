@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	@var($attributes['class'] = 'button ' .array_get($attributes, 'class', ''))
    {{ 
    	Form::input(
    		'button',
	    	$name,  
	    	$value,
	    	$attributes
	    )  
    }} 
@endcomponent