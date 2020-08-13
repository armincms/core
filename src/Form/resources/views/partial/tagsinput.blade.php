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
	'class' => null,
	'id' => null,
], (array) @$options))  

@if(! is_true(array_get('options', 'id')))
	@var($options['id'] = dot_to_id(array_get($options, 'name')))
@endif 
@var($options['class'] .= ' tagsinput') 

<p class="button-height {{ array_get($options, 'inline') }} {{ array_get($options, 'wrapper-class') }}">
	@if(is_true(array_get($options, 'inline'))) 
	<label 
		class="label {{ array_get($options, 'label-class') }}" 
		for="{{ array_get($options, 'id') }}">
		{{ array_get($options, 'label') }}
	</label>
	@endif   
	@include('form::fields.input-unstyled', compact('options')) 
</p>  
@push('scripts') 
    <script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/libs/jquery.tagsinput.js"></script>
    <script type="text/javascript"> 
        $("input#{{ $options['id'] }}").tagsInput();
    </script>
@endpush