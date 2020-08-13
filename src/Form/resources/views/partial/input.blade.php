@var($options = array_merge([
	'default' => null,
	'type' => 'text',
	'value' => null,
	'name' 	=> 'name',
	'placeholder' 	=> null,
	'inline' => 'inline-label',
	'label' => null,
	'label-class' => null,
	'input-label' => false, 
	'input-label-float' => 'right', 
	'input-label-color' => 'orange-gradient', 
	'wrapper-class' => null,
	'class' => null,
	'id' => null,
], (array) @$options))  

@if(! is_true(array_get('options', 'id')))
	@var($options['id'] = dot_to_id(array_get($options, 'name')))
@endif

<p class="button-height {{ array_get($options, 'inline') }} {{ array_get($options, 'wrapper-class') }}">
	@if(is_true(array_get($options, 'inline'))) 
	<label 
		class="label {{ array_get($options, 'label-class') }}" 
		for="{{ array_get($options, 'id') }}">
		{{ array_get($options, 'label') }}
	</label>
	@endif  
	@if(is_true(array_get($options, 'input-label'))) 
	<span class="input full-width">
		<label class="button {{ array_get($options, 'input-label-color') }} {{ array_get($options, 'input-label-float') }}" for="{{ array_get($options, 'id') }}">
			{{ $language->alias }}
		</label>
		@var($options['class'] = array_get('options', 'class', '') . ' input-unstyled')
		@include('form::fields.input', compact('options')) 
	</span> 
	@else
		@include('form::fields.input', compact('options')) 
	@endif
</p>  