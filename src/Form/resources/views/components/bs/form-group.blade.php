<div class="form-group {{ array_pull($wrapper_attributes, 'class') }} 
	{{ $errors->has($name)? 'has-error' : '' }}" {{ Html::attributes($wrapper_attributes) }}> 
	{!! $slot !!}  
	 
    <small id="{{ input_name_id($name) }}-help" class="help-block text-info bold">
    	{!! $help !!} 
    </small> 

    <span id="{{ input_name_id($name) }}-error" class="text-danger">
    	{{ $errors->first(array_get($attributes, 'name', $name)) }} 
    </span> 
</div>
 