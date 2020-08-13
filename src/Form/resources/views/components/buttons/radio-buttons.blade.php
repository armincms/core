@component('form::components.field-wrapper', compact('wrapper_attributes'))  
	@include('form::components.field-label') 
	<span class="button-group {!! array_pull($attributes, 'class') !!}" 	
			{!! Html::attributes($wrapper_attributes) !!}>
			@foreach($buttons as $button)
				@var($buttonName = array_get($button, 'name', $name))
				@var($id = str_slug($buttonName) . "-{$loop->index}") 
				@var($radio = Form::radio(
					$buttonName, 
					array_get($button, 'value'), 
					array_get($button, 'checked'), 
					array_merge(
						compact('id'), (array) array_get($button, 'attributes')
					)
				))
				{!! 
					Form::label(
						$id, 
						$radio. array_get($button, 'label') , 
						array_merge(
							['class'=>'green-active button'], 
							(array) array_get($button,'label_attributes')
						), 
						false
					)
				!!} 
			@endforeach
	</span>
@endcomponent