@extends('dashboard::layouts.main')

@section('title')
	@if(isset($version))
		@trans('actions.editing', ['content' => $version['version']])
	@else
		@trans('app::title.app_versions')
	@endif
	&nbsp;({{ $os }})
@stop

@section('breadcrumbs')
	@arminBreadcrumbs(['title' => trans("app::title.app_versions")]) 
	@endarminBreadcrumbs
	@arminBreadcrumbs(['title' => $os]) 
	@endarminBreadcrumbs
@stop

@section('main')   
	<div class="columns">
		<div class="three-columns twelve-columns-tablet">
			<form method="post" action="@if(isset($version)){{ route('{os}.update', [$os, $version['version']]) }}@else{{ route('{os}.store',[$os]) }}@endif" enctype="multipart/form-data" onsubmit="location.hash='waiting-modal'">

				{!! csrf_field() !!}
				@isset($version){!! method_field('put') !!}@endif  
				
				@var($minName = (int) $versions->max('version_name') + 1)
				<p class="block-label button-height mid-margin-top">
					<label for="version_name" class="label red">شمارگان</label>
					<input type="number" min="1" id="version_name" class="input full-width ltr" name="version_name" value="{{ old('version_name', array_get($version, 'version_name', $minName)) }}" required>
				</p> 
				<p class="block-label button-height mid-margin-top">
					<label for="version" class="label red">نسخه</label>
					<input type="text" pattern="[0-9]{1,2}.[0-9]{1,2}.[0-9]{1,3}" id="version" class="input full-width ltr" name="version" value="{{ old('version', array_get($version, 'version')) }}" required>
				</p>  

				<p class="block-label button-height mid-margin-top">
					<label for="version" class="label">فایل</label> 
					<input type="file" class="file full-width" name="app_file" accept=".apk">
					<input type="hidden" id="version-icon" name="path" value="{{ array_get($version, 'path') }}">
				</p>  
 				 
				<p class="block-label button-height mid-margin-top">
					<label for="active_version" class="label red">نسخه فعال</label>
					<input type="hidden" value="0" name="active_version">
					<input type="checkbox" class="switch" name="active_version" value="1" 
						@if(array_get($version, 'active_version', 0)) checked @endif
					data-text-on='@trans('titles.active')' data-text-off='@trans('titles.deactive')'>
				</p> 

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
					<a href="{{ route('{os}.index', $os) }}" class="button compact">
						<span class="button-icon red-gradient glossy"><span class="icon-cancel"></span></span>
						@trans('actions.cancel')
					</a>
				</p> 
			</form>
		</div>
		<div class="nine-columns twelve-columns-tablet margin-top">
			<table class="table responsive-table" id="sorting-advanced">
				<thead>
					<tr> 
						<th scope="col" class="align-center">نسخه</th> 
						<th scope="col" class="align-center" width="1%">شمارگان</th>
						<td scope="col" class="align-center hide-on-mobile-portrait" width="0">سیستم عالمل</td>
						<td scope="col" class="align-center">نسخه فعال</td>  
						<td scope="col" class="align-center hide-on-mobile" width="2%">فایل</td>  
						<th scope="col" class="align-center" width="25%">@trans('titles.actions')</th>
					</tr>
				</thead>   
				<tbody>	     
					@foreach($versions->sortByDesc('version_name') as $item)
					<tr class="{{ $item['version'] == array_get($version, 'id') ? 'blue' : '' }}">    
						<td class="vertical-center align-center">
							<strong>{{ $item['version'] }}</strong>
						</td>      
						<td class="vertical-center align-center">
							<strong>{{ $item['version_name'] }}</strong>
						</td>    
						<td class="vertical-center align-center">
							<strong>{{ $item['os'] }}</strong>
						</td>   
						<td class="vertical-center align-center">
							<span class="icon icon-{{ $item['active_version'] ? 'ok green' : 'cancel red' }}"></span>
						</td>   
						<td class="vertical-center align-center">
							@if(Storage::disk('armin.file')->has($item['path']))
								<span class="green">موجود</span>
							@else 
								<span class="red">نا موجود</span>
							@endif
						</td>   
						  
						<td class="align-center vertical-center "> 
							<form class="button-group" action="{{ route("{os}.destroy", [$os, $item['version']]) }}" method="post">
								{{ csrf_field() }} {{ method_field('delete') }} 

								<a href="{{ route("{os}.edit", [$os, $item['version']]) }}" class="button blue-gradient glossy icon-pencil with-tooltip {{ $item['version'] == $version['version'] ? 'hidden' : '' }}" title="@trans('actions.edit')"></a>

								<button type="submit" class="button red-gradient glossy icon-trash with-tooltip confirm {{ $item['version'] == $version['version'] ? 'hidden' : '' }}" title="@trans('actions.delete')" ></button>
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

 