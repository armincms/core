<p class="button-height {{ array_get(@$data, 'inline', 1) ? 'inline-label' : 'block-label' }} {{ array_get(@$data, 'wrapper-class') }}">
	<label 
		class="label {{ array_get(@$data, 'label-class') }}" 
		for="{{ dot_to_id(array_get(@$data, 'name')) }}">
		{{ array_get(@$data, 'label') }}
	</label>
	@foreach(language() as $language) 
		@var($dotname = "{$language->alias}.". array_get(@$data, 'dotname', '')) 
		<span class="input full-width translatable trans-{{$language->alias}}">
			<label class="button orange-gradient right" for="{{ dot_to_id($dotname) }}">
				{{ $language->alias }}
			</label>
			<input 
				type="text" 
				name="{{ dot_to_name($dotname) }}" 
				id="{{ dot_to_id($dotname) }}" 
				class="input-unstyled {{ array_get(@$data, 'class') }}"
				value="{{ old($dotname, array_get(@$data, $dotname)) }}" 
				placeholder="{{ array_get(@$data, 'label') }}"> 
		</span>
	@endforeach
</p>  