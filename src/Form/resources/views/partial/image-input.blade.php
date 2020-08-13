@var($options = array_merge([
	'default' => null,
	'type' => 'file',
	'value' => null,
	'old' 	=> null,
	'image' => null,
	'name' 	=> 'name',
	'placeholder' 	=> null,
	'preview' 	=> true,
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

<p class="button-height align-center profile-avatar {{ array_get($options, 'inline') }} {{ array_get($options, 'wrapper-class') }}">
	@if(is_true(array_get($options, 'inline'))) 
	<label 
		class="label {{ array_get($options, 'label-class') }}" 
		for="{{ array_get($options, 'id') }}">
		{{ array_get($options, 'label') }}
	</label>
	@endif    

	@if(is_true($options['preview']))
		<img src="{{ $options['image'] ?? "/admin/rtl/img/dragdrop.png" }}" width="200" height="200" 
			class="profile-img" alt="Image for Profile"/>
		<br>
    @endif
	<a class="button margin-top the-avatar" id="{{ $options['id'] }}">
		<span class="button-icon blue-gradient"><span class="icon-picture"></span></span>
		@trans('actions.add') 
    </a> 

	@var($options['attributes'] = 'style=display:none;') 
	@var($options['class'] .= ' uploader') 
	@include('form::fields.input', compact('options')) 

	@push('scripts') 
	<script type="text/javascript">
		$('a#{{ $options['id'] }}').click(function(event) {
			$(this).siblings('.uploader').click();
		});
	</script>
	@endpush 

	<a href="#!" class="button margin-right remove-photo">
		<span class="button-icon red-gradient"><span class="icon-trash"></span></span>
		@trans('actions.delete')
		@var($options['type'] = 'hidden') 
		@var($options['name'] .= '_old')
		@var($options['id']   = dot_to_id(array_get($options, 'name')))
		@include('form::fields.input', compact('options')) 
	</a>
</p>  
@push('scripts')   
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/resample.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/avatar.js"></script>
	 
	<script type="text/javascript">  
		$('.remove-photo').click(function(){  
			$(this).siblings('.profile-avatar').attr('src',"/{{-- {{ $_locale->direction }} --}}rtl/img/dragdrop.png");
			$(this).siblings('.uploader').val('');
			$(this).find('input').val(''); 
		});
	</script>
@endpush