@component('form::components.bs.form-group', compact(
	'wrapper_attributes', 'name', 'help', 'attributes'
))     
	@include('form::components.bs.label', compact('name', 'label'))
    {{ 
    	Form::select(
	    	$name, 
	    	(array) $values, 
	    	(array) $selected, 
	    	array_merge(
	    		['class'=>'select full-width form-control', 'id' => input_name_id($name)], 
	    		(array) $attributes
	    	), 
	    	(array) $options_attributes, 
	    	(array) $optiongroups_attributes
	    ) 
    }} 
@endcomponent