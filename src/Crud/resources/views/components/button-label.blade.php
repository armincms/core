@if(! empty($label)) 
@var($title = array_get($label, 'label', $label))
{!! 
	Form::label(
		$name, 
		$title instanceof \Illuminate\Support\HtmlString
					? $title->toHtml():armin_trans($title), 
		array_merge([
				'class' => 'button right blue-gradient glossy'
			], (array) array_get($label, 'attributes', [])
		), 
		false
	) 
!!}   
@endif 
 