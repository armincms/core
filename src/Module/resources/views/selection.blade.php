<div class="side-tabs margin-bottom tabs-active tabs-fullheight tabs-animated tab-opened"> 
	<ul class="tabs">
		@foreach(collect(config('module.locatables')) as $locatable => $locate)
		<li>
			<a href="#{{ array_get($locate, 'name', $locatable) }}-selection-tab">
				@trans(array_get($locate, 'title', $locatable))
			</a>
		</li>
		@endforeach
	</ul>
	@push('links')
	<style type="text/css">
		li a.icon-eye {display: none;}
		li:hover > a.icon-eye {display: inline-block;}
	</style>
	@endpush
	<div class="tabs-content">
		@foreach(collect(config('module.locatables')) as $locatable => $locate)
			@var($items = array_get($locate, 'items'))
			@if(is_callable($items))
				@var($items = call_user_func($items))
			@endif  
			@var($name = array_get($locate, 'name', $locatable))  
			<div id="{{ array_get($locate, 'name', $locatable) }}-selection-tab">
				<div id="cat-check-list"> 
		            <ul style="max-height: 400px; overflow: hidden scroll;">   
						<li>
							<input class="chck locate-all" name="locate[{{ $name }}][*]" value="1"
								type="checkbox" 
								@if(optional($module)->locatedInItem($name, '*')) checked @endif>

							<span>@trans('module::title.all')</span>
						</li>
		            	{!! 
		            		armin_dropdown($items, function($item) {
		            			return (array) array_get($item, 'childrens');
		            		}, function($item, $childs) use ($name, $module) {  
		            			return view(
		            				'module::dropdown-item', 
		            				compact('item', 'childs', 'name', 'module')
		            			);
		            		})
		            	!!}
		        	</ul>
		        </div>
			</div> 
		@endforeach 
	</div>
</div>	
<script type="text/javascript" src="/admin/rtl/js/tree-menu.js"></script>
