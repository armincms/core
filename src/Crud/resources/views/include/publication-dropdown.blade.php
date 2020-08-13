@if(method_exists($resource, 'trashed') && $resource->trashed())
<b class="red">@trans('admin-crud::status.trashed')</b>
@elseif(! empty($availables)) 
<a href="javascript::void(0);"
		class= 'publication-status {{ $resource instanceof \Core\Crud\Contracts\Publicatable && $resource->isPublished() ? 'green' : 'orange' }}'  
		title="@trans('admin-crud::title.publication_status')"> 
	<div class="publication-select" >  
		<select role="publication-status" data-href="{{ $href }}"
				class="select multiple easy-multiple-selection check-list"> 
			@var($title = '') 
			@foreach($availables as $available) 
				@var($value = \Core\Crud\Statuses::key($available))
				@if($value == $active)  
					@var($title = armin_trans("admin-crud::status.{$available}")) 
				@endif
				<option value="{{ $value }}" {{ $value == $active ? 'selected' : '' }}>
					@trans("admin-crud::status.{$available}") 
				</option> 
			@endforeach
		</select>
	</div>
	<b>{!! $title !!}</b>
</a>
@endempty