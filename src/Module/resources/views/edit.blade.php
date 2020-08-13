@extends('admin-crud::edit') 

@section('title')
	@if($resource = $form->getModel())
		@trans("admin-crud::title.editing_resource", ['id' => $resource->id])
	@else
		@trans('admin-crud::title.creating_resource')
	@endif
	
@stop 

@section('breadcrumbs')
	@if(Route::has("{$name}.index"))
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans($title)@endslot 
		{{ route("{$name}.index") }}
	@endcomponent 
	@endif
	@component('dashboard::components.breadcrumbs')
		@slot('title')
			<b class=green>
				@trans('admin-crud::action.' .($resource? 'edit':'create'))
			</b>
		@endslot 
	@endcomponent 
@stop

@section('main') 


	{!! 
		$form->open([
			'route' => $resource ? ["{$name}.update", $resource] : "{$name}.store",
			'method'=> $resource ? 'put' : 'post',
			'files'  => true
		])
	!!} 

	@component('admin-crud::components.fixed-buttons') 
		{!! $actions->map->toHtml()->implode('') !!} 
	@endcomponent

		  
	{{-- tab form --}}

	<div class="with-padding">
		{!! $form->doBuild()->render($form->rows()->keys()->toArray()) !!}
	</div>
	@var($form->doBuild()->childs()->map->doBuild())
	<div class="side-tabs margin-bottom tabs-active tabs-animated tab-opened margin-top">
		<ul class="tabs"> 
			@foreach($form->childs() as $child)  
			<li>
				<a href="#tab-{{ $child->getName() }}"> 
					@if($title = $child->getTitle())
						{{ armin_trans($title) }}
					@else
						{{ $child->getName() }}
					@endif
				</a>
			</li> 
			@endforeach
		</ul>
		<div class="tabs-content">
			@foreach($form->childs() as $child)  
				<div id="tab-{{ $child->getName() }}" class="with-padding">
					{!! $child !!}
				</div>
			@endforeach
		</div>
	</div>     
	{!! $form->close() !!}
@overwrite 
 