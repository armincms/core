@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label')  
	@var($attributes['class'] = 'switch ' . array_get($attributes, 'class'))
	{{ Form::hidden($name, $off, ['id' => "{$name}{$off}"]) }}  
	{{ Form::checkbox($name, $on, $checked, $attributes) }}     
@endcomponent