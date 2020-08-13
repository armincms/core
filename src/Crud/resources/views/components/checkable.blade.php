@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	@component('admin-crud::components.field-help') 
		{!! $help !!}
	@endcomponent 
	@if($translatable)
		@foreach($languages as $language)
		@endforeach
	@else
		<span class="button-group {{ array_get($attributes, 'class') }}" 
				{{ Html::attributes($attributes) }}>
			@foreach($checkables as $key => $checkable)
				@if(! is_array($checkable))
					@var($checkable = ['label' => $checkable])
				@endif
				@var ($name  = array_get($checkable, 'name', $name))
				@var ($value = array_get($checkable, 'value', $key))
				@var ($label = array_get($checkable, 'label', $value))
				@var($attrs = array_get($checkable, 'attributes')) 
				@var($isChecked = in_array($value, (array) $checked)? true : null) 
				@var($attrs['id'] = input_name_id("{$name}-{$value}"))
				@var($type = $radio ? 'radio' : 'checkbox')
				<label {!! Html::attributes($attrs) !!}
						class="button green-active {{ array_get($attrs, 'class') }}">
					{!! $label instanceof \Illuminate\Support\HtmlString ? $label->toHtml() : armin_trans($label) !!} 
					{!! Form::{$type}($name, $value, $isChecked, $attrs) !!} 
				</label>
			@endforeach
		</span>
	@endif
@endcomponent