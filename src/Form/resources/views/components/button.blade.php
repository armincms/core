@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label')  
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