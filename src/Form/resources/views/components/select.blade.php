@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label')  
    {{ 
    	Form::select(
	    	$name, 
	    	$values, 
	    	$selected, 
	    	array_merge(
	    		['class'=>'select full-width multiple-as-single easy-multiple-selection allow-empty check-list'],
	    		(array) $attributes
	    	), 
	    	(array) $options_attributes, 
	    	(array) $optiongroups_attributes
	    ) 
    }} 
@endcomponent