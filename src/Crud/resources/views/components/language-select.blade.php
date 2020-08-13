@if($languages->count() > 1) 
<select id="{{ str_slug($name) }}-language" 
		class="select pull-{{ isset($pull) ? $pull : 'left' }} compact orange-gradient"
		role="translatable-field">
	@foreach($languages as $language)
		<option value="{{ $language->alias }}" 
				@if(App::getLocale() == $language->alias) selected @endif>
			{{ $language->title }}
		</option>
	@endforeach
</select>
@endif