@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')  
	<label for="" class="label blue" style="margin-right: -25px !important; width: 20px">
		@component('admin-crud::components.field-help') 
			{!! $help !!}
		@endcomponent 
	</label>
	@if(0 && $translatable)  
		@foreach($languages as $language)
		{!! 
	    	Form::select(
		    	input_name_prefix($language->alias, $name),
		    	$values, 
		    	empty($selected) ? null : $selected, 
		    	array_merge(
		    		['class'=>'select full-width multiple-as-single easy-multiple-selection allow-empty check-list'],
		    		(array) $attributes
		    	), 
		    	(array) $options_attributes, 
		    	(array) $optiongroups_attributes
		    ) 
	    !!} 
		@endforeach  
		@include('admin-crud::components.language-select')

	@else
	@var($id = array_get($attributes, 'id'))
	@empty($id)
		@var($id = input_name_id(array_get($attributes, 'name', $name)))
	@endempty
    {!! 
    	Form::select(
	    	$name, 
	    	$values, 
	    	empty($selected) ? null : $selected, 
	    	array_merge(
	    		[
	    			'class'=>'select full-width multiple-as-single easy-multiple-selection allow-empty check-list',
	    			'id' => $id
	    		],
	    		(array) $attributes
	    	), 
	    	(array) $options_attributes, 
	    	(array) $optiongroups_attributes
	    ) 
    !!} 
	@endif
@endcomponent