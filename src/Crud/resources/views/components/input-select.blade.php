@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')    
	<span class="input full-width">   
		@component('admin-crud::components.field-help') 
			{!! $help !!}
		@endcomponent  
		@include('admin-crud::components.button-label', ['label' => $input_label]) 
		@if(! empty($select))
			@var($class = array_get($select, 'attributes.class', 'select compact pull-left'))
			@var($id = array_get($select, 'attributes.name'))
			@empty($id)
			@var($id = input_name_id(array_get($select, 'attributes.name', "select-{$name}")))
			@endempty
			{!! 
				Form::select(
					array_get($select, 'name', $name),
			        (array) array_get($select, 'values', []),
			        array_get($select, 'selected'),
			        (array) array_get($select, 'attributes') + compact('class', 'id'),
			        (array) array_get($select, 'options_attributes'),
			        (array) array_get($select, 'optiongroups_attributes')
				)
			!!}
		@endif
		@var($label = array_get($label, 'label', $label))
		@var($placeholder = is_string($label)? armin_trans($label):data_get($label, 'label', $name))
		@if($translatable)  
			@foreach($languages as $language)
			@var($inputName = input_name_prefix($language->alias, $name))
			@var($val = is_array($value) ? array_get($value, $language->alias) : $value) 
			{!!  
				Form::{$type}( 
					"{$language->alias}::{$name}",
					$val, 
					array_merge([
						'class' => 'input-unstyled',
						'data-translatable'  => $language->alias,
						'role'  => "auto-width",
						'name'  => $inputName,
						'id'  	=> input_name_id($inputName),
						'placeholder' => $placeholder, 
					], $attributes)
				)
			!!} 
			@endforeach  
			@include('admin-crud::components.language-select')  
		@elseif($type === 'password' || $type == 'file')
			{!!
				Form::{$type}( 
					$name,  
					array_merge([
						'class' => 'input-unstyled',
						'role'  => "auto-width", 
						'placeholder' => $placeholder,
					], $attributes)
				)
			!!}
		@else
			{!!
				Form::{$type}( 
					$name, 
					$value, 
					array_merge([
						'class' => 'input-unstyled',
						'role'  => "auto-width", 
						'placeholder' => $placeholder,
						'id' => input_name_id(array_get($attributes, 'name', $name))
					], $attributes)
				)
			!!}
		@endif 
	</span> 
@endcomponent 