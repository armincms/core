@extends('dashboard::layouts.main')

@section('title')
	@if(isset($menu))
		@trans('actions.editing', ['content' => $menu['id']])
	@else
		@trans('app::title.app_menus')
	@endif
	
@stop

@section('breadcrumbs')
	@arminBreadcrumbs(['title' => trans("app::title.app_menus")]) 
	@endarminBreadcrumbs
@stop

@section('main')   
	<div class="columns">
		<div class="three-columns twelve-columns-tablet">
			<form method="post" action="@if(isset($menu)){{ route('app-menu.update', [$menu['id']]) }}@else{{ route('app-menu.store') }}@endif" enctype="multipart/form-data">

				{!! csrf_field() !!}
				@isset($menu){!! method_field('put') !!}@endif
				
				@var($menuPage = array_get($menu, 'page', optional($pages->first())->id))
				<p class="block-label button-height mid-margin-top">
					<label for="menu-title" class="label red">برگه</label>
					<select name="page" id="pages" class="select full-width">
						@foreach($pages as $page)
						<option value="{{ $page->id }}" @if($page->id == $menuPage) selected @endif>{{ $page->title }}</option>
						@endforeach
					</select> 
				</p> 

				<p class="block-label button-height mid-margin-top">
					<label for="menu-order" class="label red">ترتیب</label>
					<input type="number" id="menu-order" class="input full-width" name="order" value="{{ old('order', array_get($menu, 'order', $menus->max('order') + 1)) }}" required min="0">
				</p> 

				<p class="block-label button-height mid-margin-top">
					<label for="menu-title" class="label">@trans('titles.icon')</label> 
					<input type="file" class="file full-width" name="icon_file">
					<input type="hidden"  id="menu-icon" name="icon" value="{{ array_get($menu, 'icon') }}">
				</p> 

 				@var($icon = array_get($menu, 'icon'))
 				@if(Storage::disk('armin.image')->has($icon))
				<div class="input pull-left block-label with-small-padding" style="position: relative;"> 
	 				<img src="{{ Storage::disk('armin.image')->url($icon) }}" height="100" class="pull-left">
	 				<i class="button icon-trash red-gradient tiny remove-icon" style="position: absolute;"></i>
				</div>
				<br><br>
 				@endif 
				@push('scripts')
				<script type="text/javascript">
					$(document).ready(function() { 
						$('.remove-icon').click(function(event) {
							/* Act on the event */
							$(this).parent().hide();
							$('#menu-icon').val('');
						});

						$('span.file').find('span.button').click(function(event) {
							/* Act on the event */
							$(this).siblings('input[type=file]').click();
						});
					});
				</script>
				@endpush  

				<p class="button-height align-left margin-top">
					<button type="submit" name="next_action" value="save" class="button compact">
						<span class="button-icon green-gradient glossy">
							<span class="icon-ok"></span>
						</span>
						@trans('actions.save')
					</button>
					<button type="submit" name="next_action" value="save&new" class="button compact">
						<span class="button-icon orange-gradient glossy">
							<span class="icon-ok"></span>
						</span>
						@trans('actions.save&new')
					</button>  
					<a href="{{ route('app-menu.index') }}" class="button compact">
						<span class="button-icon red-gradient glossy"><span class="icon-cancel"></span></span>
						@trans('actions.cancell')
					</a>
				</p> 
			</form>
		</div>
		<div class="nine-columns twelve-columns-tablet margin-top">
			<table class="table responsive-table" id="sorting-advanced">
				<thead>
					<tr> 
						<th scope="col" width="25%">
							@trans('titles.id')
						</th> 
						<th class="hidden"></th>
						<td>برگه</td>
						<th>ترتیب</th> 
						<th>@trans('titles.icon')</th> 
						<th scope="col" width="15%" class="align-center">
							@trans('titles.actions')
						</th>
					</tr>
				</thead>   
				<tbody>	     
					@foreach($menus as $item)
					<tr class="{{ $item['id'] == array_get($menu, 'id') ? 'blue' : '' }}">     
						<td class="vertical-center">
							<strong>{{ $item['id'] }} </strong> 
						</td>   
						<td class="hidden"></td>
						<td class="vertical-center">
							{{ optional($pages->find(array_get($item, 'page')))->title }}
						</td>
						<td class="vertical-center">{{ array_get($item, 'order', 99) }}</td> 
						<td>
							@if(Storage::disk('armin.image')->has($item['icon']))
							<img src="{{ Storage::disk('armin.image')->url($item['icon']) }}" height="50">
							@endif
						</td> 
						  
						<td class="align-center vertical-center "> 
							<form class="button-group" action="{{ route("app-menu.destroy", $item['id']) }}" method="post">
								{{ csrf_field() }} {{ method_field('delete') }} 

								<a href="{{ route("app-menu.edit", [$item['id']]) }}" class="button blue-gradient glossy icon-pencil with-tooltip {{ $item['id'] == $menu['id'] ? 'hidden' : '' }}" title="@trans('actions.edit')"></a>

								<button type="submit" class="button red-gradient glossy icon-trash with-tooltip confirm {{ $item['id'] == $menu['id'] ? 'hidden' : '' }}" title="@trans('actions.delete')" ></button>
							</form>
						</td>
					</tr> 
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop 

@include('backend.includes.table-script')

 