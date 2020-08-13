<div class="body">
	@for ($i = 1; $i < 4; $i++)
		@var($section = 'header'. $i) 
		@component('template::front.section', compact('section')) 
			@var($width = $this->containerWidth($section))
			@component('template::front.container', compact('section', 'width')) 
				{!! $this->renderedModules($section) !!}
			@endcomponent
		@endcomponent
	@endfor

	@component('template::front.section', ['section' => 'menu'])
		@var($section = 'menu')
		@var($width = $this->containerWidth($section))
		@component('template::front.container', compact('section', 'width')) 
			{!! $this->renderedModules($section) !!}
		@endcomponent
	@endcomponent

	@for ($i = 1; $i < 11; $i++)
		@var($section = 'top'. $i) 
		@component('template::front.section', compact('section')) 
			@var($width = $this->containerWidth($section))
			@component('template::front.container', compact('section', 'width')) 
				{!! $this->renderedModules($section) !!}
			@endcomponent
		@endcomponent
	@endfor

	@include('template::front.middle')

	@for ($i = 1; $i < 11; $i++)
		@var($section = 'footer'. $i)
		@component('template::front.section', compact('section'))
			@var($width = $this->containerWidth($section))
			@component('template::front.container', compact('section', 'width')) 
				{!! $this->renderedModules($section) !!}
			@endcomponent
		@endcomponent
	@endfor

	@var($section = 'copyright')
	@component('template::front.section', compact('section'))
		@var($width = $this->containerWidth($section))
		@component('template::front.container', compact('section', 'width')) 
			{!! $this->renderedModules($section) !!}
		@endcomponent
	@endcomponent
</div> 
