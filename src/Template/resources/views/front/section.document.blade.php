@yield("before-{$section}")

@if($this->sectionIsActive($section) && strlen($slot))
	@var($codePosition = $this->setting("{$section}.code.position", 'bottom'))
	@var($background = (array) $this->setting("{$section}.background"))    
	<{{ $sectionTag ?? 'div' }} id="{{ $section }}" 
		class="section-{{ $section }} {{ $width ?? '' }} {{ $this->inactiveGrades($section)->implode(' ') }}" 
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
	      @endswitch> 
		<div class="{{ $section }} @if($this->setting("{$section}.style.active")){{ 'pstyle'.$this->setting("{$section}.style.type") }}@endif">
			
			{{ $codePosition == 'top'? $this->setting("{$section}.code.content") : null }}

			{{-- before-slot --}}
			@yield("before-{$section}-slot")

			{!! $slot !!}

			{{-- after-slot --}}
			@yield("after-{$section}-slot")

			{{ $codePosition != 'top'? $this->setting("{$section}.code.content") : null }}

		</div>
	</{{ $sectionTag ?? 'div' }}> 
@endif

@yield("after-{$section}") 