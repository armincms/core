@if(! empty($label)) 
@var($label = is_array($label) ? $label : compact('label'))
@var($title = array_get($label, 'label', $label)) 
{!! 
	Form::label(
		input_name_id(array_get($attributes, 'name', $name)),  
		$title instanceof \Illuminate\Support\HtmlString
					? $title->toHtml():armin_trans($title), 
		array_merge([
				'class' => 'label '. ($translatable ? 'translatable-label' : '') 
			], (array) array_get($label, 'attributes', [])
		), 
		false
	) 
!!}   
@endif 
 