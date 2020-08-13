<li  class=dd-item>
	<span class='button-height mid-margin-right red pull-right menu-item-title'>
		{{ $menu->title }}
	</span>
	<span class="icon-cancel-circled size-1 remove-menu-item red pull-left mid-margin-top mid-margin-left"></span>
	<span class="icon-cog edit-menu-item blue pull-left mid-margin-top mid-margin-left"></span>
	<span class="icon-plus add-menu-item green pull-left mid-margin-top mid-margin-left"></span>
	<div class="dd-handle"></div> 
	<div class="menu-data full-width hidden with-padding" 
			style="background:#fafafa; border: 1px solid #ccc">  
		@var($data = ['id', 'title', 'url', 'icon', 'menu_item_id', 'level','depth'])

		@foreach ($menu->only($data) as $key => $value) 
			@if($key == 'depth')  
				@var($value = $depth)
			@elseif($key == 'url') 
				@var($value = $menu->url())
			@endif
			<p class="inline-label button-height @if(in_array($key, ['id', 'menu_item_id', 'level', 'depth'])){{ 'hidden' }}@endif 
			">
				<label class=label>{{ $key }}</label>
				<input type=text class="input full-width menu-item-{{ $key }} {{ $key !== 'url' or 'ltr' }}" name="menu[{{ $menu->id }}][{{ $key }}]" value='{{ $value }}' />
			</p>

		@endforeach  
	</div> 
	@empty($childs){{ '' }}@else{!! "<ol class='dd-list'>{$childs}</ol>" !!}@endif 
</li>