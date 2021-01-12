{{-- clone item --}} 
<div class="menu-item-clone hidden">
	<li class=dd-item>
		<i></i>
		<span class="icon-cog edit-menu-item  blue pull-left mid-margin-top mid-margin-left"></span>
		<span class="icon-plus add-menu-item green pull-left mid-margin-top mid-margin-left"></span>
		<span class='button-height mid-margin-right red pull-right menu-item-title'>__title__</span>
		<div class="dd-handle"></div>  
		<div class="menu-data full-width hidden with-padding" 
				style="background:#fafafa; border: 1px solid #ccc">  
				@var($data = [
					'id' => 0, 
					'title' => 1, 
					'url' => 1, 
					'icon' => 1, 
					'menu_item_id' => 0, 
					'level' => 0, 
					'depth' => 0, 
					'menuable_type' => 0, 
					'menuable_id' => 0
				]) 
			@foreach ($data as $key => $status)  
				<p class="inline-label button-height {{ $status ? '' : 'hidden' }}">
					<label class=label>{{ $key }}</label> 
					<input class='input full-width menu-item-{{ $key }} {{ $key == 'url' ? 'ltr' : '' }}' name="menu[__id__][{{ $key }}]" value="__{{ $key }}__"/>
				</p> 
			@endforeach  
		</div>  
    </li>
</div>
{{-- end clone item --}}

@if(count($menuables))
<div class="four-columns pull-right"> 
	<div class="input search full-width mid-margin-bottom">
		<label for="search" class="button orange-gradient icon-search right"></label>
		<input type="text" id="search" class="input-unstyled" 
				placeholder="@trans('titles.search')"> 
	</div>
	<dl class="accordion same-height">  
		@foreach($menuables as $key => $value) 
			@var($title = array_get($value, 'title', $key))
			@var($callback = array_get($value, 'callback'))  
			@continue(! is_callable($callback)) 
			@var($items = call_user_func_array($value['callback'], [$resource]))

			@if(count($items))
				<dt>@trans($title)</dt> 
				<dd class="with-small-padding">  
					<div class="cat-checks menulist" 
							style="border-color:#efefef; border-style:solid;
					                border-width:1px; height:250px; overflow:auto; padding:0.5em 0.5em;">
		            	<div id="cat-check-list">  
							{!! 
								armin_dropdown($items, function($item) {  
										return (array) array_get($item, 'childs', []);
									}, function($item, $childs) { 
									echo view('menu::item', compact('item', 'childs'))->render(); 
								})
							!!} 
						</div>
					</div> 
		            <p class="button-height compact mid-margin-top align-center">
						<a role=select-all class="button icon-ok blue-gradient glossy"> 
		                	@trans('titles.select_all')
		                </a>
						<a role=deselect-all class="button mid-margin-right icon-cancel red-gradient glossy"> 
							@trans('titles.deselect_all')
						</a>
						<a role=add-menu class="button mid-margin-right icon-plus green-gradient glossy">
							@trans('actions.add')
						</a> 
					</p>
				</dd>
			@endif
		@endforeach

	</dl>
</div> 
@endif 

@push('scripts') 
<script src="/admin/rtl/js/mark.js"></script> 
<script type="text/javascript">
		$(function() {
		
			var mark = function() { 
			    var $list = $('.cat-checks').unmark();
			    $list.find('li').addClass('hidden');

			    if(this.value.length > 0) {
			    	$list.mark(this.value, {
			    		acrossElements : true,
				    	each: function(marked) {    
			    			var $li = $(marked).closest('li');
			    			$li.removeClass('hidden').parents('li.hidden').toggleClass('hidden');
			    			$li.siblings('li').filter(function(){
				    				return $(this).find('mark').length == 0;
				    			}).addClass('hidden');

			    			$(marked).parents('ul:not(.showcat)').siblings('i').click();
				    	}  
				    });
			    } else {   
			    	$list.find('li').removeClass('hidden');
			    	$list.find('.showcat').filter(function(){ 
			    		return $(this).parents('ul').length == 1;
			    	}).siblings('i').click(); 
			    }
			} 
			
			$("#search").on("keyup", mark);     
		});

	$(document).ready(function() {
		$('[role=select-all]').click(function(event) {
			/* Act on the event */
			$(this).closest('dd').find('input[type=checkbox]').prop('checked', true)
		});
		$('[role=deselect-all]').click(function(event) {
			/* Act on the event */
			$(this).closest('dd').find('input[type=checkbox]').prop('checked', false)
		});
		$('[role=add-menu]').click(function(event) {
			/* Act on the event */
			var $container = $('#nestable_list_1').find('ol:first');
			var $cloned = $('.menu-item-clone').html();  
			var $menus = $container.children('li');

			$(this).closest('dd').find('input:checked').each(function(index) {
				var $level 	= parseInt($menus.length) + parseInt(index);
				var $data 	= $(this).data();
				var $id 	= $data.id 		|| null;
				var $title 	= $data.title 	|| $(this).siblings('span').text(); 
				var $url 	= $data.url 	|| null;
				var $type 	= $data.type 	|| null; 
				console.log($url)
				var $item   = $cloned
								    .replace(/__id__/g, 'id_' + $level)
								    .replace(/__title__/g, $title)
								    .replace(/__url__/, $url)
								    .replace(/__menuable_id__/, $id)
								    .replace(/__menuable_type__/, $type)
								    .replace(/__level__/, $level) 
								    .replace(/__depth__/, 0) 
								    .replace(/__menu_item_id__/, 0) 
								    .replace(/__[^_]+__/g, '') 

				$container.append($item); 
			});
			$container.children('li:last').find('.edit-menu-item').click();
			$(this).siblings('[role=deselect-all]').click();
		});

		$(document).on('click', 'li.dd-item > i', function() {
			$(this).parent().remove()
		})
	});
</script>
@endpush
