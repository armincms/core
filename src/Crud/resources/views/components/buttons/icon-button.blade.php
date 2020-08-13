@php
	$attributes['class']= 'button ' .array_get($attributes, 'class', '');
	$attributes['name']	= 'next_action';
	$attributes['value']= $name ?? 'save'; 
@endphp
<button {!! Html::attributes($attributes) !!}>
	<span class="button-icon icon-{{ $icon ?? 'floppy' }} {{ $color ?? 'green' }}-gradient"></span>
	{!! $label !!}
</button>  