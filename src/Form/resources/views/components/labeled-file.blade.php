@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label')  
	@php  
		$input_label_attributes['class'] = 'button right '. array_get(
			$input_label_attributes, 'class', 'orange-gradient'
		); 
		if($multiple) {
			$attributes[] = 'multiple';
		}
		$attributes['accept'] = implode((array) $accepted, ',');

	@endphp
		<span class="input-unstyled"> 
			@isset($input_label)
			{{ Form::label($name, $input_label, $input_label_attributes) }}
			@endisset 
			{{ 
				Form::file( 
					$name,  
					array_merge(['class' => 'file'], $attributes)
				) 
			}} 
		</span>  
@endcomponent