@foreach((array) $options['children'] as $child)
    @if(! in_array($child->getRealName(), (array) $options['exclude'])) 
        {!! $child->render() !!}  
    @endif
@endforeach 
@var(return)

@if($showLabel && $showField)
    @if($options['wrapper'] !== false)
    <p {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if($showLabel && $options['label'] !== false)
    {!! Form::label($name, $options['label'], $options['label_attr']) !!}
@endif

@if($showField)
    @foreach((array) $options['children'] as $child)
        @if(! in_array($child->getRealName(), (array) $options['exclude'])) 
            {!! $child->render() !!}  
        @endif
    @endforeach 

    @include('form::basis.help-block') 

@endif

@include('form::basis.errors') 

@if($showLabel && $showField)
    @if($options['wrapper'] !== false)
    </p>
    @endif
@endif
