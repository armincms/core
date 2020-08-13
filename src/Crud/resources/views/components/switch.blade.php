@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')   
	@component('admin-crud::components.field-help') 
		{!! $help !!}
	@endcomponent  
	@var($attributes['data-text-on'] = armin_trans('admin-crud::title.active'))
	@var($attributes['data-text-off']= armin_trans('admin-crud::title.inactive'))
	@if($translatable)   
		@var($attributes['class'] = 'switch medium small-margin-top ' .array_get($attributes, 'class'))
		@foreach($languages as $language) 
			<span class="input-unstyled" data-translatable={{ $language->alias }}>
			{!! 
				Form::hidden(input_name_prefix($language->alias, $name), $off, [
					'id' =>"{$language->alias}-{$name}-off"
				]) 
			!!}  

			@isset($attributes['id'])
			@else
				@var($attributes['id'] = input_name_id("{$language->alias}-{$name}-on"))
			@endif

			{!! 
				Form::checkbox(
					input_name_prefix($language->alias,$name), $on, $checked, $attributes
				) 
			!!}   
			</span>
		@endforeach  
		@include('admin-crud::components.language-select', ['pull' => ''])   
	@else
		@var($attributes['class'] = 'switch ' . array_get($attributes, 'class'))
		{{ Form::hidden($name, $off, ['id' => "{$name}-{$off}"]) }}  

		@isset($attributes['id'])
		@else
			@var($attributes['id'] = input_name_id("{$name}-on"))
		@endif

		{{ Form::checkbox($name, $on, $checked, $attributes) }}    
	@endif 
@endcomponent