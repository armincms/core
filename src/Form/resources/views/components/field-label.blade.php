@if(! is_null($label)) 
{!! 
	Form::label($name, armin_trans($label), array_merge(
		['class' => 'label'], (array) $label_attributes
	), false) 
!!}   
@endif 
 