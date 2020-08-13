@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	@component('admin-crud::components.field-help') 
		{!! $help !!}
	@endcomponent 
	@if($translatable)
		@foreach($languages as $language)
		@endforeach
	@else  
		{!! Form::checkbox($name, $value, $checked, $attributes) !!}
	@endif
@endcomponent