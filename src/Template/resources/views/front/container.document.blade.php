@yield("before-{$section}")

@if(strlen($slot)) 
	<div id="{{ $section }}-container" 
		class="{{ $section }}-container  {{ $width ?? '' }} {{ $section == 'mainwrapper' ? 'p-0' : '' }}"> 
		<div id="{{ $section }}-row" class="{{ $section }}-row {{ $section == 'mainwrapper' ? '' : 'row' }}">   
			@yield("before-{$section}-slot")

			{!! $slot !!}

			@yield("after-{$section}-slot") 	
		</div> 
	</div>
@endif

@yield("after-{$section}") 