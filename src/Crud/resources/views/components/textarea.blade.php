@if($translatable) 
	@isset($wrapper_attributes['class'])
		@var($wrapper_attributes['class'] .= ' relative')
	@else
		@var($wrapper_attributes['class'] = 'inline-label button-height relative')
	@endif 
@endif
@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')     
		@component('admin-crud::components.field-help') 
			{!! $help !!}
		@endcomponent  
		@if(! empty($input_label))
		@var($input_label = is_array($input_label)? $input_label : ['label' => $input_label])
		@var($class = array_get($input_label, 'attributes.class')) 
		@empty($class)
			@var($input_label['attributes'] = [
				'class' =>  'pull-left button glossy blue-gradient',
				'style' => 'margin-bottom:-30px',
			])
		@endempty
		@endif
		@include('admin-crud::components.button-label', ['label' => $input_label]) 
		@var($placeholder = is_string($label)? armin_trans($label):data_get($label, 'label', $name))
		@if($translatable)  
			@foreach($languages as $language)
			@var($inputName = input_name_prefix($language->alias, $name))
			{!!  
				Form::textarea( 
					"{$language->alias}::{$name}",
					$value, 
					array_merge([
						'class' => 'input full-width autoexpanding',
						'data-translatable'  => $language->alias,
						'name'	=> $inputName,
						'id'	=> input_name_id($inputName),
						'placeholder' => armin_trans(is_string($label) ? $label : array_get($label, 'label'))
					], $attributes)
				)
			!!} 
			@endforeach 
			<span style="position: absolute; bottom: 3px; left: 8px;">
				@include('admin-crud::components.language-select')
			</span>
			 
		@else
			{!!
				Form::textarea( 
					$name, 
					$value, 
					array_merge([
						'class' => 'input full-width autoexpanding' 
					], $attributes)
				)
			!!}
		@endif  
@endcomponent 