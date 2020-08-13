<textarea   
	name="{{ dot_to_name(array_get($options, 'name')) }}" 
	id="{{ array_get($options, 'id') }}" 
	class="input full-width {{ array_get($options, 'class') }}" 
	placeholder="{{ array_get($options, 'placeholder', array_get($options, 'label')) }}"
	data="{{ array_get($options, 'data') }}" 
	{{ implode(' ', (array) array_get($options, 'attributes', []))  }}
	>{{ array_get($options, 'value', array_get($options, 'default')) }}</textarea> 