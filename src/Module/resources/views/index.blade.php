@extends('dashboard::layouts.main')

@section('title')@trans($title)@stop 

@section('breadcrumbs')  
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans($title)@endslot 
	@endcomponent 
@stop

@section('main') 
	@component('admin-crud::components.fixed-buttons') 
		<a href="#instances" class="button with-tooltip margin-left">
			<span class="button-icon  green-gradient glossy right-side">
				<span class="icon-plus"></span>
			</span>
			@trans('admin-crud::title.new_resource', ['resource' => armin_trans($title)])
		</a> 
	@endcomponent
	{!! $table !!}

	<!--modal-->
	<div class="remodal" data-remodal-id="instances" role="dialog" aria-labelledby="title" 
		aria-describedby="description">
	  	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	 	<div class="remodal-body">
	   		<h3 id="title">@trans('titles.select module')</h3>
	   		<div id="description">
				<div class="dlform">
					<div class="columns"> 
						@foreach(app('module.repository')->all() as  $module) 
							<div class="six-columns no-margin "> 
								@var($img = "/modules/{$module->name()}/{$module->name()}.jpg")
								@if(! File::exists(public_path($img)))
								@var($img = 'https://dummyimage.com/150x150/efefef/fff&text='. $module->name())
								@endif
								<img src="{{ $img }}" width="75" height="75" class="module-logo pull-right with-small-padding"> 
								<div class="with-small-padding" style="position: static; left: 0; right: 80px; text-align: right;"> 
									<h6>
										<a href="{{ route('module.create', $module->name()) }}">
											{{ $module->label() }} 
											<small style="font-size: 8px;">  v <span class="red ltr"> {{ $module->version() }}</span></small>
										</a> 
									</h6>  
									<span>
										@if($author = $module->author())
										<span>@trans('module::title.author'):</span> {{ $author }}
										<br> 
										@endif 
										@if($email = $module->email())
										<span>@trans('module::title.email'):</span> {{ $email }} 
										<br>
										@endif 
									</span>
									@if($description = $module->description())
										@trans('module::title.description') : 
										<small>{{  Helper::readmore($description, 20, 50, '...') }}</small>
									@endif
								</div>
							</div> 
						@endforeach 
					</div>
				</div>
			</div>
		</div>
	</div> 
@stop 

@push('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('img.module-logo').on('mouseenter', function(event) {
			/* Act on the event */
			$('#cloned-logo').remove()
			$img = $(this).clone();
			$img = $img.attr('id', 'cloned-logo');
			$offset = $(this).offset(); 
      

			$img = $img.css({
				position: 'absolute',
				top: $offset.top + 20 + 'px',
				left: $offset.left - 220 + 'px',
				width: '200px',
				height: 'auto',
				zIndex: 9999999999999999
			});

			$('body').append($img);		
		}).on('mouseleave', function(event) {	
			$('#cloned-logo').remove()
		})
	});
</script>
@endpush