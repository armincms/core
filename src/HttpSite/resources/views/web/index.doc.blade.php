<!DOCTYPE html>
<html lang="{!! $this->getLocale() !!}" dir="{!! $this->direction() !!}">
<head>  
	<meta charset="{!! $this->getCharset() !!}">
	<title>{!! $this->title() !!}</title> 
	{!! $this->getMetaString() !!}
	{!! $this->headerAssets()->map->toHtml()->implode('') !!}
</head>
<body>
	{!! $this->content() !!} 

	{!! $this->footerAssets()->map->toHtml()->implode('') !!} 
	{!! $__template->getModules()->implode('') !!}
</body>
</html> 