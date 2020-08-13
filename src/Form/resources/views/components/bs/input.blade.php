@component('form::components.bs.form-group', compact(
	'wrapper_attributes', 'name', 'help', 'attributes'
))      
	@include('form::components.bs.label', compact('name', 'label'))   
	

	@component('form::components.bs.input-group-addon', [
		'addon' => is_array($input_label) ? $input_label : ['label' => $input_label]
	])
		{!! 
	    	Form::input(
	    		$type,
		    	$name, 
		    	$value,  
		    	array_merge(
		    		['class'=>'select full-width form-control', 'id' => input_name_id($name)], 
		    		(array) $attributes 
		    	), 
		    	(array) $attributes 
		    ) 
	    !!}
	@endcomponent
@endcomponent
