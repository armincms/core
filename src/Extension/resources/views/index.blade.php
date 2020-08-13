@extends('dashboard::layouts.main')  

@section('title')@trans("extension::title.extensions")@stop

@section('breadcrumbs')
	@arminBreadcrumbs(['title' => armin_trans("extension::title.extensions")])@endarminBreadcrumbs 
@stop

@section('main')
	{{-- @var($_buttons = [ 
		'only' => ['new'],
		'new' => [
			'active'=> true, 
			'link' => route("extension.installer"),
			'type' => 'a',
		] 
	]) 
	@include('backend.includes.action-button', compact('_buttons'))  --}}  
		<table class="table responsive-table" id="sorting-advanced">
			<thead>
				<tr>
					<th scope="col" width="2%"><input type="checkbox" name="check-all" id="check-all" value="1"></th>
					<th scope="col" width="25%">
						@trans('titles.title')
					</th>
					<th scope="col" width="12%" class="align-center hide-on-mobile">
						@trans('titles.author')
					</th>  
					 
					<th scope="col" width="20%" class="align-center hide-on-tablet">
						@trans('titles.date')
					</th> 
				</tr>
			</thead>   
			<tbody>	    
				@foreach(['module', 'default', 'template'] as $extension)    
						<th scope="row" class="checkbox-cell align-center vertical-center"><input type="checkbox" name="checked[]" id="check-1" value=""></th>
						<td class="vertical-center">{{ $extension }}</td>   
						<td class="vertical-center">{{ $extension }}</td>   
						<td class="vertical-center">
							<form action="{{ route('extension.installer') }}" class="button-group">
								<input type="hidden" value="1" name="extensions[0]">
								<button class="button" type="submit">install</button>
							</form> 
						</td>    
					</tr> 
				@endforeach
			</tbody>
		</table>   
@stop