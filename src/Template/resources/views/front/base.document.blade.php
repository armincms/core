<!DOCTYPE html>
<html lang="{!! $this->getLocale() !!}" dir="{!! $this->direction() !!}">
<head>  
	<meta charset="{!! $this->getCharset() !!}">
	<title>{!! $this->title() !!}</title>    
	{!! $this->getMetaString() !!}  
	{!! $this->headerAssets()->map->toHtml()->implode('') !!}  
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
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
	@empty(trim(Request::path(), '/'))<h1 class="hidden">{{ \Armincms\Nova\General::option('_app_title_') }}</h1>@endif
	@include('template::front.body') 
	
 
	{!! $this->footerAssets()->map->toHtml()->implode('') !!} 
</body>
</html> 