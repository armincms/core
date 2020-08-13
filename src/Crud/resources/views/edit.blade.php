@extends('dashboard::layouts.main') 

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
		{{ route("{$name}.index", $routeParameters) }}
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
			'route' => array_merge(
				[$resource? "{$name}.update" : "{$name}.store"], $routeParameters ?? []
			),
			'method'=> $resource ? 'put' : 'post',
			'files'  => true
		])
	!!} 

	@component('admin-crud::components.fixed-buttons') 
		{!! $actions->map->toHtml()->implode('') !!} 
	@endcomponent

	@include('admin-crud::include.messages')

	@if($form instanceof \Core\Crud\Contracts\AccordionForm)
		<div class="columns"> 
			{{-- accardion form --}}
			<div class="eight-columns">
				{!! $form->doBuild()->render($form->rows()->pluck('name')->toArray()) !!} 
			</div>
			<div class="four-columns">
				<dl class="accordion">
					@foreach($form->childs()->map->doBuild() as $child) 
					<dt>
						@if($title = $child->getTitle())
							{{ armin_trans($title) }}
						@else
							{{ $child->getName() }}
						@endif
					</dt>
					<dd class="with-small-padding mid-margin-top mid-margin-bottom">
						{!! $child !!}
					</dd>
					@endforeach
				</dl>
			</div>
		</div>
		@elseif($form instanceof \Core\Crud\Contracts\TabForm)
		{{-- tab form --}}
		@var($form->doBuild()->childs()->map->doBuild())
		<div class="side-tabs margin-bottom tabs-active tabs-animated tab-opened">
			<ul class="tabs">
				<li>
					<a href="#tab-{{ $form->getName() }}">
						@if($title = $form->getTitle())
							{{ armin_trans($title) }}
						@else
							{{ $form->getName() }}
						@endif 
					</a>
				</li>
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
				<div id="tab-{{ $form->getName() }}" class="with-padding">
					{!! $form->render($form->rows()->pluck('name')->toArray()) !!}
				</div>
				@foreach($form->childs() as $child)  
					<div id="tab-{{ $child->getName() }}" class="with-padding">
						{!! $child !!}
					</div>
				@endforeach
			</div>
		</div> 
		@else
		{{-- simple form --}}
		<div class="columns">
			<div class="twelve-columns">{!! $form->render() !!}</div>
		</div> 
		@endif     
	{!! $form->close() !!}
@stop 

@push('links') 
	{!! $form->styles() !!}
	{!! $form->childs()->map->styles()->implode('') !!}
@endpush

@push('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(this).on('mouseover', '[role=translatable-field]', function(event) {
			$(this).addClass('syncing')
		});
		$(this).on('change', '[role=translatable-field]', function(event) {
			/* Act on the event */ 
			var $lang = this.value; 
			var $wrap = $(this).closest('p');
			var $targets = ['input', 'textarea', 'label.button', 'span.input-unstyled'];
			$wrap.find($targets.join(',')).each(function() {
				if($trans = $(this).data('translatable')) {  
					if($trans == $lang) {
						$(this).trigger('isVisible').show();
						
						$wrap.find('label.translatable-label').attr(
							'for', $(this).attr('id')
						);
					} else {
						$(this).trigger('isInvisible').hide();
					} 
				}

			}); 
			if($(this).closest('.select-cloned').length > 0) { 
				$('[role=translatable-field]').filter(function() {
					return $(this).closest('.select-cloned').length === 0;
				}).val($lang).trigger('change')
					.closest('span').trigger('change');
			}
			
		});
		$('[role=translatable-field]').change();

		$(this).on('click', 'label.translatable-label', function(event) { 
			$(this).parent().find('input:visible').focus();
		});
	});
</script> 
{!! $form->scripts() !!}
{{-- {!! $form->childs()->map->scripts()->implode('') !!}  --}}
@endpush