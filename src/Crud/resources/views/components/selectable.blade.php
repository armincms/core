@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label', ['translatable' => false])   
	<selectable {!! Html::attributes($attributes) !!} 
		:config='@json($config)' 
		:label="'@trans($label)'" 
		:options='@json($options)'
		:name="'{{ $name }}'" 
		:selected='@json(array_values((array) $selected))' 
		:disabled='@json(array_values((array) $disabled))' 
		:max-height="50"></selectable>
@endcomponent
