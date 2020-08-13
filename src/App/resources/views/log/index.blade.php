@extends('dashboard::layouts.main')

@section('title')
	@if(isset($log))
		@trans('actions.editing', ['content' => $log->title])
	@else
		@trans('armin::title.app_logs')
	@endif
	
@stop

@section('breadcrumbs')
	@arminBreadcrumbs(['title' => trans("armin::title.app_logs")]) 
	@endarminBreadcrumbs
@stop 

@section('main')     
	<div class="columns">
		<div class="three-columns six-columns-tablet twelve-columns-mobile-portrait">
					<span class="button huge full-width green-gradient glossy">
						<p class="align-right margin-right icon-cart">آنلاین</p>
						<span class="list-count white-gradient glossy margin-right margin-left">
							{{ 
								$logs->where('status', 1)->filter(function($log) {
									if($log->updated_at->format('Ymd') == now()->format('Ymd')) {
										return true;
									}

									$log->update(['status' => 0]);

									return 0;
								})->count() 
							}}
						</span>
					</span>
		</div>
		<div class="three-columns six-columns-tablet twelve-columns-mobile-portrait">
			<span class="button huge full-width blue-gradient glossy">
				<p class="align-right margin-right icon-star">نصب شده</p>
				<span class="list-count white-gradient glossy margin-right margin-left">
					{{ $logs->count() }}
				</span>
			</span>
		</div>
		<div class="three-columns six-columns-tablet twelve-columns-mobile-portrait">
			<span class="button huge full-width red-gradient glossy">
				<p class="align-right margin-right icon-forbidden">نصب امروز</p>
				<span class="list-count white-gradient glossy margin-right margin-left">
					{{ 
						$logs->filter(function($log) { 
							return $log->created_at->format('ymd') == Carbon\carbon::now()->format('ymd');
						})->count()
					}}
				</span>
			</span>
		</div>
		@var($max = $logs->filter(function($log) {
			return ! empty($log->city);
		})->groupBy('city')->max()) 
		
		<div class="three-columns six-columns-tablet twelve-columns-mobile-portrait">
			<span class="button huge full-width orange-gradient glossy">
				<p class="align-right margin-right icon-refresh">
					بیشترین نصب | <b>{{ $max ? $max->first()->city : '' }}</b>
				</p> 
				<span class="list-count white-gradient glossy margin-right">
					 {{ optional($max)->count() }}
				</span>
			</span>
		</div>
	</div> 
	{!! $table !!} 
@stop   