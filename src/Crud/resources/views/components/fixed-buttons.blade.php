@if(trim($slot->toHtml()))
	<div class="wrapped-columns top-nav-container large-margin-bottom">  
		<div class="mp-1-1 dl-1-2 no-padding">
			<div class="with-padding"> 
				<p class="button-height float-right">
					{!! $slot !!}
				</p>
			</div>
		</div> 
		<div class="clearfix"></div>
	</div>
@endisset