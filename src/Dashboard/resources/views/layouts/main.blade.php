@include('dashboard::inc.header')  
<!-- Main content -->
<section role="main" id="main">  
	<hgroup id="main-title" class="thin">
		<h1>@yield('title')</h1>
		<h2><strong>@format(null, 'd')</strong>@format(null, 'F')</h2><span>@time</span>
	</hgroup>
	<!-- Start breadcrumbs --> 
	<div class="breadcramp margin-bottom hide-on-mobile-portrait">
		<span class="icon-home white"></span>
		<a href="{{ route('panel') }}" class="back-text">@trans('dashboard::title.dashboard')</a>
		@yield('breadcrumbs') 
	</div>
	<div class="with-padding margin-top">
		<!-- End breadcrumbs --> 
		{{-- errors of actions --}}
		@include('backend.notifications.errors')
		{{-- errors of actions --}}  
		{{-- errors of messages --}}
		@include('backend.notifications.messages')
		{{-- errors of messages --}} 
		<div class="dashboard-static">   
			 @yield('main')
		</div>
	</div> 
</section>
<!-- End main content --> 
 
<!-- Side tabs shortcuts -->
<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-left"> 
	@foreach(collect(config('armin.admin.panel.shortcuts', []))->sortBy('order') as $key => $value) 
		@var($title = armin_trans(array_get($value, 'title', $key))) 

		@var($url = array_get($value, 'url', 'javascript:void(0);'))
		@if($route = array_get($value, 'route'))
			@var($url = call_user_func_array('route', (array) $route)) 
		@elseif(is_callable($url))
			@var($url = call_user_func($url))  
		@endif 
		<li class="{{ URL::current() == $url || $loop->first ? 'current' : '' }}" id="{{ str_slug($key) }}"> 
			<a href="{{ $url ?? 'javascript:void(0);' }}" 
				class="{{ array_get($value, 'class') }}" title="{{ $title ?? $key }}">{{ $title ?? $key }}
			</a>
		</li> 
	@endforeach 
</ul>  
<!-- End Side tabs shortcuts -->  

<!-- Sidebar/drop-down menu -->
@include('dashboard::inc.big-side')
<!-- End sidebar/drop-down menu -->

@include('dashboard::inc.footer') 