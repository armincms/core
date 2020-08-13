@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label', ['translatable' => false])   
	<tags-input {!! Html::attributes($attributes) !!}  
		:label="'@trans($label)'"  
		:name="'{{ $name }}'" 
		:tags='@json(array_values((array) $selected))'></tags-input>
@endcomponent 