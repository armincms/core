@if(! is_null($label)) 
	@var($label = is_array($label) ? $label : compact('label'))
	@var($title = array_get($label, 'label', $label))
	{!! 
		Form::label(
			$name,  
			is_string($title)? armin_trans($title) : $title, 
			array_merge(
				['class' => 'control-label', 'for' => input_name_id($name)], 
				(array) array_get($label, 'attributes', [])
			), 
			false
		) 
	!!}   
@endif 
