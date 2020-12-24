@if($content = trim($this->__module->getContent()))
	@php   
		$columns = [];
		$baseColumn = (int) $this->setting($this->__module->get('position').".basecolumn", 12);

		foreach (config('armin.template.responsive') as $responsive => $name) { 
			if($column = $this->__module->params("_config.{$responsive}.column")) {
				$columns[] = $baseColumn == 0 || ! is_numeric($column)  
						? "{$responsive}-hidden"
						: "{$responsive}-" . responsive_class((int) $column, $baseColumn);
			} 			
		}   
	@endphp 
	<div id="module{{ $this->__module->get('id') }}" class="module {{ implode(' ', $columns?: ['mp-1-1']) }}  mt-p-0 {{ $this->__module->config('_floating') }} {{ $this->__module->config('_direction') }} {{ $this->__module->config('_align') }} 
		@if($effectType = $this->__module->config('_effect.effect')) wow {{ $effectType }}@endif"
		@if($this->__module->config('_effect.effect'))
			data-wow-duration="{{ $this->__module->config('_effect.delay', 250)/1000 }}s" 
			data-wow-delay="{{ $this->__module->config('_effect.delay', 250)/1000 }}s" 
			data-wow-iteration="{{ $this->__module->config('_effect.repeat', 1) == 'infinite' ? 99999 : 1 }}"
		@endif>    
		<div class="module{{ $this->__module->get('id') }} w-100"> 
			@if((boolean) $this->__module->config('_show_title') || (boolean) $this->__module->config('_show_description') || $this->__module->config('_show_icon'))
				<div class="moduletitle">
					@yield('before-icon')
					@if($this->__module->get('show_icon', 0))
						@switch($this->__module->get('icon_type'))
							@case('icon')
								<i class="icon-{{ $this->__module->get('module_icon') }}"></i>
								@break
							@case('image')
								<img src="{{ Storage::disk('armin.image')->url($this->__module->get('icon_image')) }}" alt="{{ $this->__module->get('title') }}">
								@break
							@default
								@break
						@endswitch
					@endif
	 
	 				@var($title = $this->__module->get('title'))
					@if((boolean) $this->__module->config('_show_title') && $title)
						@var($tag = $this->setting(
							$this->__module->get('position').".title_font.tag", 'h3'
						))
						<{{ $tag }} class="title">{{ $this->__module->get('title') ?? '' }}</{{ $tag }}>
					@endif
	 				@var($description = $this->__module->get('description'))
					@if((boolean) $this->__module->config('_show_description') && $description) 
						@var($tag = $this->setting(
							$this->__module->get('position').".description_font.tag", 'h3'
						))
						<{{ $tag }} class="description">{{ $description }}</{{ $tag }}>
					@endif 
				</div>    
			@endif     
			{!! $content !!} 
		</div>
	</div>
@endif