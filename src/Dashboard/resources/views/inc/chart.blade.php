<div class="full-width">
	@foreach((array) config('admin.panel.charts', []) as $chart)
		@includeIf($chart)
	@endforeach
</div> 
