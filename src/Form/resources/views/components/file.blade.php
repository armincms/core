@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label') 
	@php
		if($multiple) {
			$attributes[] = 'multiple';
		}
		$attributes['accept'] = implode((array) $accepted, ',');

	@endphp 
	{{ 
		Form::file( 
			$name,  
			array_merge(['class' => 'file full-width'], $attributes)
		) 
	}}    
@endcomponent