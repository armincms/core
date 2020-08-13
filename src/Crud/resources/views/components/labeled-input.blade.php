@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	@php  
		$input_label_attributes['class'] = 'button right '. array_get(
			$input_label_attributes, 'class', 'orange-gradient'
		); 
	@endphp
	<span class="input full-width">  
		@isset($input_label)
		{{ Form::label($name, $input_label, $input_label_attributes, false) }}
		@endisset
		{{ 
			Form::input(
				array_get($attributes, 'type', 'text'),
				$name, 
				$value, 
				array_merge(['class' => 'input-unstyled', 'size' => 10], $attributes)
			) 
		}}    
	</span>  
@endcomponent