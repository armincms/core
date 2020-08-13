@foreach(language() as $language) 
<p class="button-height {{ array_get(@$data, 'inline', 1) ? 'inline-label' : 'block-label' }} {{ array_get(@$data, 'wrapper-class') }}  translatable trans-{{ $language->alias }} ">
	<label 
		class="label {{ array_get(@$data, 'label-class') }}" 
		for="{{ dot_to_id(array_get(@$data, 'name')) }}">
		{{ array_get(@$data, 'label') }}
	</label>
		@var($dotname = "{$language->alias}.". array_get(@$data, 'dotname', ''))  
		<input 
			type="text" 
			name="{{ dot_to_name($dotname) }}" 
			id="{{ dot_to_id($dotname) }}" 
			class="tagsinput {{ array_get(@$data, 'class') }}"
			value="{{ old($dotname, array_get(@$data, $dotname)) }}" 
			placeholder="{{ array_get(@$data, 'label') }}">  
</p>
@endforeach   
@push('scripts') 
    <script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/libs/jquery.tagsinput.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/libs/form-component.js"></script>
@endpush