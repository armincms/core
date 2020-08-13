<div class="input-group col-xs-12"> 
	{!! $slot !!} 

	@var($label = array_get((array) $addon, 'label'))
	@var($attrs = array_get((array) $addon, 'attributes', []))
    
    @if(! is_null($label))    
    	<div class="input-group-addon {{ array_pull($attrs, 'class') }}" 
    		{!! Html::attributes($attrs) !!}>
    		@if(is_string($label))
    			@trans($label)
    		@else
    			{!! $label !!}
    		@endif
    	</div> 
	@endif 
</div> 