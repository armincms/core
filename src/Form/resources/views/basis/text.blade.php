@if($showLabel && $showField)
    @if($options['wrapper'] !== false) 
    	<p {!! $options['wrapperAttrs'] !!} 
    		@if(array_get($options, 'multilingual'))  
    			data-multilingual="{{ $options['language'] ?? 'fa' }}"
    		@endif 
    	> 
    @endif
@endif 
	@if($showLabel && $options['label'] !== false)
		{!! Form::label($name, $options['label'], $options['label_attr']) !!} 
	@endif

	@if(array_get($options, 'multilingual'))
		<span {!! Html::attributes($options['attr']) !!}> 
			{!! Form::label($name, $options['language'], ['class' => 'button glossy orange-gradient right']) !!}

			{!! Form::input($type, $name, $options['value'], ['class' => 'input-unstyled']) !!}  
	    
	    	@include('form::basis.help-block')
		</span> 
	@else($showField)
	    {!! Form::input($type, $name, $options['value'], $options['attr']) !!}  
	    @include('form::basis.help-block')
	@endif

	@include('form::basis.errors')

@if($showLabel && $showField)
    @if($options['wrapper'] !== false) </p> @endif
@endif
