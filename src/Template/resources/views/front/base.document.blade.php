<!DOCTYPE html>
<html lang="{!! $this->getLocale() !!}" dir="{!! $this->direction() !!}">
<head>  
	<meta charset="{!! $this->getCharset() !!}">
	<title>{!! $this->title() !!}</title>  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta property="og:title" content="{{ $this->title() }}"> 
    <meta name="description" content="{{ $this->description() }}">
    <meta property="og:description" content="{{ $this->description() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 

	{!! $this->getMetaString() !!}  
	{!! $this->headerAssets()->map->toHtml()->implode('') !!}  
</head>

@var($background = (array) $this->setting("background"))   
<body id="body" class="style{{ $this->setting('style.active_style', 0) }} {!! $this->direction() !!}"
	@switch(array_get($background, 'background'))
		@case('slide')
            role="slider-image" 
            data-slide=@json([
	            'delay' => array_get($background, 'slide.time', 5),
	            'count' => (int) array_get($background, 'slide.count')
            ]) 
        	@break 
        @case('image')
        	@if(array_get($background, 'image.animation') == 'smooth')
	            role="smooth-image" data-smooth=@json([
	                'direction' => array_get($background, 'image.direction'),
	                'speed' => (int) array_get($background, 'image.speed', 10),
	            ]) 
        	@endif
        	@break
      @endswitch
	>
	
	@include('template::front.body') 
	
 
	{!! $this->footerAssets()->map->toHtml()->implode('') !!} 
</body>
</html> 