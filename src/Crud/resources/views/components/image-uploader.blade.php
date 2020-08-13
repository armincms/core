@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label')     
	@if($translatable)  
		@foreach($languages as $language)
			<media-manager 
				:multiple={{ $multiple ?? false }} 
				:value=@json((array) $values) 
				v-bind:input-name="'{{ input_name_prefix($language->alias,$name) }}'"
				v-bind:base-url="'{{ Storage::disk('armin.file')->url('/') }}'"
				v-bind:height="'{{ array_get($attributes, 'wdith', '100%') }}'"
				v-bind:width="'{{ array_get($attributes, 'height', '200px') }}'"
			></media-manager> 
		@endforeach  
		@include('admin-crud::components.language-select')  
	@else 
		<media-manager 
				:multiple='{{ $multiple ? 'true' : 'false' }}' 
				:value='@json(is_array($values) ? $values : $values->toArray())' 
				v-bind:input-name="'{{ $name }}'"
				v-bind:base-url="'{{ Storage::disk('armin.image')->url('/') }}'"
				v-bind:disk="'armin.image'"
				v-bind:width="'{{ array_get($attributes, 'width', '100%') }}'" 
				v-bind:height="'{{ array_get($attributes, 'height', '200px') }}'"
				v-bind:title="'{{ armin_trans(
					array_get($attributes, 'button', 'admin-crud::title.image')
				) }}'" 
			></media-manager>  
	@endif  
	@component('admin-crud::components.field-help') 
		{!! $help !!}
	@endcomponent    
@endcomponent 