@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	{{ 
		Form::{$type}( 
			$name,  
			array_merge(['class' => 'input full-width'], $attributes)
		) 
	}}     
@endcomponent