@php 
	$label = Lang::has($label) && ! is_array(trans($label))? trans($label) : $label;   
@endphp
<p {!! Html::attributes(array_merge(['class' => 'inline-label button-height'], $wrapper_attributes)) !!}>
	@isset($label) 
    {{ Form::label($name, $label, array_merge(['class' => 'label'], $label_attributes)) }} 
	@endisset     
</p>