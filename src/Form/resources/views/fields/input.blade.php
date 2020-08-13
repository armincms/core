<input 
	type="{{ array_get($options, 'type', 'text') }}" 
	name="{{ dot_to_name(array_get($options, 'name')) }}" 
	id="{{ array_get($options, 'id', dot_to_id(array_get($options, 'name'))) }}" 
	class="input {{ array_get($options, 'class') }}"
	value="{{ array_get($options, 'value', array_get($options, 'default')) }}" 
	placeholder="{{ array_get($options, 'placeholder', array_get($options, 'label')) }}"
	data="{{ array_get($options, 'data') }}" 
	{{ implode(' ', (array) array_get($options, 'attributes', []))  }}
	> 