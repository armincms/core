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
	@var($options['attributes'] = ['size=' . ($options['inline'] == 'inline-label' ? 15 : 22)])
@endif

<p class="button-height {{ array_get($options, 'inline') }} {{ array_get($options, 'wrapper-class') }}">
	@if(is_true(array_get($options, 'inline'))) 
	<label 
		class="label {{ array_get($options, 'label-class') }}" 
		for="{{ array_get($options, 'id') }}">
		{{ array_get($options, 'label') }}
		<span class="icon-help-circled size-1 pull-left with-tooltip orange" title="{{ $options['help'] }}"></span>
	</label>
	@endif   
	<span class="input full-width"> 
		<label class="button {{ array_get($options, 'input-label-color') }} {{ array_get($options, 'input-label-float') }}" for="{{ array_get($options, 'id') }}">
			{{ $language->alias }}
		</label>   
		<span class="icon-calendar left"></span>
		@include('form::fields.input-unstyled', compact('options')) 
	</span>   
</p>
@push('links')
	<link rel="stylesheet" href="/admin/rtl/css/persianDatepicker-default.css" />
@endpush 
@push('scripts')  
<script type="text/javascript" src="/admin/rtl/js/persianDatepicker.min.js"></script>
<script type="text/javascript">   
    $("#{{ $options['id'] }}").persianDatepicker({ 
	        showGregorianDate: !0,
	        persianNumbers: !0,
	        formatDate: "YYYY/MM/DD hh:ss",
	        selectedBefore: !1,
	        selectedDate: null,
	        startDate: null,
	        endDate: null,
	        prevArrow: '\u25c4',
	        nextArrow: '\u25ba',
	        theme: 'default',
	        alwaysShow: !1,
	        selectableYears: null,
	        selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
	        cellWidth: 25, // by px
	        cellHeight: 25, // by px
	        fontSize: 13, // by px                
	        isRTL: !1,
	        calendarPosition: {
	            x: 0,
	            y: 0,
	        },
	        onShow: function () { },
	        onHide: function () { },
	        onSelect: function () { }
	});  
</script>
@endpush