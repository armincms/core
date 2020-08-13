@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label') 
	{{ 
		Form::{$type}( 
			$name, 
			$value, 
			array_merge(['class' => 'input full-width'], $attributes)
		) 
	}}   
@endcomponent