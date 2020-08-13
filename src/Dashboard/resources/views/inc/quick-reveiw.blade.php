<ul class="stats split-on-mobile">
	@foreach(config('admin.panel.quick_review', []) as $title => $value) 
		<li>
			<a href="javascript:void(0);">
				<strong class="orange" style="font-size: 33px; line-height: 33px">{{ (int) $value }}</strong>{!! Lang::has($title) ? trans($title) : $title !!}
			</a>
		</li>
	@endforeach 
</ul> 