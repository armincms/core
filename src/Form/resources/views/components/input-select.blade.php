@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label') 
	@php  
			$input_label_attributes['class'] = 'button right '. array_get(
				$input_label_attributes, 'class', 'orange-gradient'
			); 
	@endphp
	
	<span class="input full-width">  
		@isset($input_label)
		{{ Form::label($name, $input_label, $input_label_attributes) }}
		@endisset
		{{ 
			Form::input(
				array_get($attributes, 'type', 'text'),
				$name, 
				$value, 
				array_merge(['class' => 'input-unstyled', 'size' => 10], $attributes)
			) 
		}}   
		{{   
			call_user_func_array([Form::class, 'select'], [
				'name' 		=> array_get($select, 'name', "{$name}_options"),
				'values' 	=> (array) array_get($select, 'values', []),
				'selected' 	=> (array) array_get($select, 'selected', []),
				'attributes'=> ['class' => 'select pull-left compact'],
			]) 
		}} 
	</span>  
@endcomponent 