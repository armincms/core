<span class="input full-width">  
	@foreach($input_labels as $label)
	@endforeach
	{!! $slot !!}
	<select id="{{ str_slug($name) }}-language" 
			class="select pull-left compact orange-gradient"
			role="translatable-field">
		@foreach($languages as $language)
			<option value="{{ $language->alias }}" 
					@if(App::getLocale() == $language->alias) selected @endif>
				{{ $language->title }}
			</option>
		@endforeach
	</select>
</span>