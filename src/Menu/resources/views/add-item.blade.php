@extends('admin-crud::edit')
@push('links')
	<style type="text/css">
		.dd-item span  {
			cursor: pointer;
		} 
		.dd-item > button:before  {
			font-family: 'FontArmin';
		}
		.dd-item > button[data-action="collapse"]:before {
			content: '\e80a' !important;  
		}
		.dd-item > button[data-action="expand"]:before {
			content: '\e80c' !important;  
		}
		#fake-menu-item button {display: none;}
		#cat-check-list {list-style: none;}
	</style>
@endpush 

@section('main') 
	{!! 
		$form->open([
			'route' =>  ["{$name}.item.store", $resource] ,
			'method'=> 'post' 
		])
	!!} 

	@component('admin-crud::components.fixed-buttons') 
		{!! $actions->map->toHtml()->implode('') !!} 
	@endcomponent

	@include('admin-crud::include.messages')
	
 	@include('menu::menuables')

	<div class="{{ count($menuables) ? 'eight' : 'twelve' }}-columns pull-left">  
		<div class="box-body clearfix">
        	<div id="dataList"></div>
			<div class="dd" id="nestable_list_1">  
				<li class=dd-item id="fake-menu-item">  
					<span class="icon-plus add-menu-item green pull-right"><b>افزودن منو</b></span> 
					<div class="menu-data full-width clearfix ignore underline blue-gradient">    
						<p class="inline-label button-height hidden">  
							<input type="hidden" class='menu-item-depth' value="0">
						</p>  
					</div> 
					<ol class="dd-list"> 

							{!!  
								armin_dropdown($resource->items->all(),
									function($menu) {
										return $menu->childs->all();
									}, function($menu, $childs, $depth) {
									return view('menu::menu-item', compact('menu', 'childs', 'depth'));
								})
							!!} 
					</ol>
				</li>
			</div>
		</div>
	</div>
	{!! $form->close() !!}
	<div class="clearfix"></div>
@stop


@push('scripts')  
<script src="/admin/rtl/js/nestable-ltr.js"></script> 
<script type="text/javascript"> 
	jQuery(document).ready(function() {     
		$(document).on('click', '.remove-menu-item', function(event) {
			event.preventDefault();
			/* Act on the event */ 
			$(this).closest('.dd-item').confirm({
				onConfirm: function() {
					$(this).fadeAndRemove();
				} 
			})
		});
		$(document).on('keyup', '.menu-item-title', function(event) {
			event.preventDefault();
			/* Act on the event */
			$(this).closest('.menu-data').siblings('.menu-item-title').text(this.value); 
		});
	   	$('#nestable_list_1').nestable({
		   	dropCallback : function(data) { 
		   		var $el		= $(data.sourceEl[0]);
		   		var $data	= $el.children('.menu-data');
		   		var $parentData	= $el.parent().siblings('.menu-data');
		   		var $parent = $parentData.find('input.menu-item-id').val() || 0;
		   		var $depth 	= parseInt($parentData.find('input.menu-item-depth').val() || -1) + 1;

		   		$data.find('input.menu-item-menu_item_id').attr('value', $parent).val($parent);
		   		$data.find('input.menu-item-depth').attr('value', $depth).val($depth);

		   		$el.parent().children('li').each(function(i) {
		   			$(this).children('.menu-data').find('input.menu-item-level')	
		   						.attr('value', i).val(i).change();
		   		});
		   	},
            group		: 0,
            maxDepth	: 5,
            threshold	: 20
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', 'span.edit-menu-item', function(event) {
			event.preventDefault();
			/* Act on the event */    
			var $data = $(this).siblings('.menu-data')
						.toggleClass('clearfix').toggleClass('hidden');

			var $id = $data.find('.menu-item-id').val();
			$data.find('.menu-item-title').focus();

			$('#nestable_list_1').find('.menu-data:not(.hidden)').each(function() {   
				if(! $(this).hasClass('ignore') && $(this).find('.menu-item-id:first').val() != $id)
					$(this).addClass('hidden').removeClass('clearfix');  
			});		
		});

		$(document).on('click', '.dd-item > [data-action=collapse], .dd-item > [data-action=expand], .dd-item > .icon-plus ', function(event) {
			event.preventDefault();
			/* Act on the event */  
			$(this).siblings('.menu-data').not('.ignore').removeClass('clearfix').addClass('hidden');
		});

		$(document).on('click', '.add-menu-item', function(event) {
			event.preventDefault();
			/* Act on the event */  
			var $data 	= $(this).siblings('.menu-data')
			var $depth 	= $(this).siblings('.menu-data').find('input.menu-item-depth').val();

			if($depth > 3) return;
			var $parent = $data.find('.menu-item-id').val() || 0; 
			var $siblings = $(this).siblings('.dd-list').children('li');

			var $id =  $parent +'_'+ ($siblings.length + 1);  
			var $title = 'new menu ' + ($parent? $parent + '-':'') + ($siblings.length + 1);   

			var $menu = $('.menu-item-clone').html()
						.replace(/__title__/g, $title)
						.replace(/__menu_item_id__/g, $parent)
						.replace(/__id__/g, $id)
						.replace(/__level__/g, 0)
						.replace(/__depth__/g, parseInt($depth) + 1)
						.replace(/_{2}[a-z0-9_]+_{2}/gi, '');

			$siblings.each(function(i, item) {  
				$(this).find('input.menu-item-level').attr('value', i + 1).change(); 
			});

			var $ol = $(this).siblings('ol');

			if($ol.length == 0) {
				var $item = $(this).closest('.dd-item');
				$ol = $('<ol>', {class: 'dd-list'});
				$item .prepend(
						$('<button>', {
							type: 'button', "data-action": 'collapse', text:'Collapse'
						})
					)
				 	.prepend(
				 	 	$('<button>', {
				 	 		type: 'button', "data-action": 'expand', text: 'Expand'
				 	 	}).hide()
				 	)
					.append($ol);
			}

			$menu = $($menu);

			console.log($menu)

			$ol.prepend($menu);

			$menu.find('.edit-menu-item').click();
					// .parent().find('p:not(.hidden)').first().find('input:first').focus()
					// 	.closest('li').siblings('li').find('.menu-data.clearfix:not(.ignore)')
					// 		.siblings('.edit-menu-item').click(); 
		}); 
	}); 
</script>
{!! $form->scripts() !!}
{!! $form->childs()->map->scripts()->implode('') !!}
@endpush