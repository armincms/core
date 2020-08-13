@var($options = array_merge([
	'default' => null,
	'type' => 'text',
	'value' => null,
	'name' 	=> 'name',
	'placeholder' 	=> null,
	'inline' => 'inline-label',
	'label' => null,
	'label-class' => null,
	'input-label' => false, 
	'input-label-float' => 'right', 
	'input-label-color' => 'orange-gradient', 
	'wrapper-class' => null,
	'auto-resizing' => true,
	'class' => null,
	'id' => null,
], (array) @$options))  

@if(! is_true(array_get('options', 'id')))
	@var($options['id'] = dot_to_id(array_get($options, 'name')))
@endif

@if(is_true($options['auto-resizing']))
	@var($options['class'] .= ' autoexpanding')
	@push('scripts') 
		<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.auto-resizing.js"></script>
	@endpush
@endif 

<p class="button-height {{ $options['inline'] }} {{ $options['wrapper-class'] }}">
	@if(is_true($options['inline'])) 
	<label class="label {{ $options['label-class'] }}" for="{{ array_get($options, 'id') }}">
		{{ $options['label'] }}
	</label> 
	@endif
	@include('form::fields.textarea', compact('options'))  
</p>   