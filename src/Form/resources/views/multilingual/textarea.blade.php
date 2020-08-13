<p class="button-height {{ array_get(@$data, 'inline', 1) ? 'inline-label' : 'block-label' }} {{ array_get(@$data, 'wrapper-class') }}">
	<label 
	class="label {{ array_get(@$data, 'label-class') }}" 
	for="{{ dot_to_id(array_get(@$data, 'name')) }}">
	{{ array_get(@$data, 'label') }}
	</label> 
	@foreach(language() as $language)
		@var($dotname = "{$language->alias}.". array_get(@$data, 'dotname', '')) 
		<textarea  
			name="{{ dot_to_name($dotname) }}" 
			id="{{ dot_to_id($dotname) }}" 
			class="input full-width autoexpanding translatable trans-{{$language->alias}} {{ array_get(@$data, 'class') }}" 
			placeholder="{{ array_get(@$data, 'label') }}"  
		>{{ old($dotname, array_get(@$data, $dotname)) }}</textarea>  
	@endforeach
</p>  
@push('scripts') 
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.auto-resizing.js"></script>
@endpush