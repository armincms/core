<div class="body">
	@for ($i = 1; $i < 4; $i++)
		@var($section = 'header'. $i) 
		@if($renderedModules = $this->renderedModules($section))
			@component('template::front.section', compact('section')) 
				@var($width = $this->containerWidth($section))
				@component('template::front.container', compact('section', 'width')) 
					{!! $renderedModules !!}
				@endcomponent
			@endcomponent
		@endif
	@endfor

	@component('template::front.section', ['section' => 'menu'])
		@var($section = 'menu')
		@var($width = $this->containerWidth($section))
		@if($renderedModules = $this->renderedModules($section))
			@component('template::front.container', compact('section', 'width')) 
				{!! $this->renderedModules($section) !!}
			@endcomponent
		@endif
	@endcomponent

	@for ($i = 1; $i < 11; $i++)
		@var($section = 'top'. $i) 
		@if($renderedModules = $this->renderedModules($section)) 
			@component('template::front.section', compact('section')) 
				@var($width = $this->containerWidth($section))
				@component('template::front.container', compact('section', 'width')) 
					{!! $this->renderedModules($section) !!}
				@endcomponent
			@endcomponent
		@endif
	@endfor

	@include('template::front.middle')

	@for ($i = 1; $i < 11; $i++)
		@var($section = 'footer'. $i)
		@if($renderedModules = $this->renderedModules($section))
			@component('template::front.section', compact('section'))
				@var($width = $this->containerWidth($section))
				@component('template::front.container', compact('section', 'width')) 
					{!! $this->renderedModules($section) !!}
				@endcomponent
			@endcomponent
		@endif
	@endfor

	@var($section = 'copyright') 
	@if($renderedModules = $this->renderedModules($section))
		@component('template::front.section', compact('section'))
			@var($width = $this->containerWidth($section))
			@component('template::front.container', compact('section', 'width')) 
				{!! $this->renderedModules($section) !!}
			@endcomponent
		@endcomponent
	@endif
</div> 
