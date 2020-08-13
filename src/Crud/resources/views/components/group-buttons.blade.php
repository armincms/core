@component('admin-crud::components.field-wrapper', compact('wrapper_attributes'))  
	@include('admin-crud::components.field-label') 
	<span class="button-group {!! array_pull($attributes, 'class') !!}" 	
			{!! Html::attributes($attributes) !!}>
			@var($buttonType = $radio ? 'radio' : 'checkbox')
			@if($translatable)
				@foreach($buttons as $button)
					@var($realName = $radio? $name : array_get($button, 'name', $name))
					@foreach($languages as $language)
						@var($buttonName=input_name_prefix($language->alias, $realName))
						@var($id = str_slug($buttonName) . "-{$loop->index}") 
						@var($checkable = Form::{$buttonType}(
							$buttonName, 
							array_get($button, 'value'), 
							array_get($button, 'checked'), 
							array_merge(
								compact('id'), (array) array_get($button, 'attributes')
							)
						))
						@var($buttonLabel = armin_trans(
							array_get($button, 'label', array_get($button, 'value', $name))
						)) 
						{!! 
							Form::label(
								$id, 
								$checkable.$buttonLabel , 
								array_merge([
										'class'=>'green-active button', 
										'data-translatable'  => $language->alias
									], (array) array_get($button,'label_attributes')
								), 
								false
							)
						!!} 
					@endforeach
				@endforeach  
				@include('admin-crud::components.language-select') 
			@else
				@foreach($buttons as $button)
					@var($buttonName = $radio ? $name : array_get($button, 'name', $name))
					@var($id = str_slug($buttonName) . "-{$loop->index}") 
					@var($checkable = Form::{$buttonType}(
						$buttonName, 
						array_get($button, 'value'), 
						array_get($button, 'checked'), 
						array_merge(
							compact('id'), (array) array_get($button, 'attributes')
						)
					))
					@var($buttonLabel = armin_trans(
						array_get($button, 'label', array_get($button, 'value', $name))
					)) 
					{!! 
						Form::label(
							$id, 
							$checkable. $buttonLabel, 
							array_merge(
								['class'=>'green-active button'], 
								(array) array_get($button,'label_attributes')
							), 
							false
						)
					!!} 
				@endforeach
			@endif
			
	</span>
@endcomponent