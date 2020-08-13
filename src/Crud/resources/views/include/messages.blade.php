@if($errors->count())
	<p class="big-message red-gradient"> 
		<a  title="Hide message" class="close show-on-parent-hover">✕</a> 
		<span class="block-arrow"><span></span></span> 
		<span class="big-message-icon icon-ok"></span> 
		<strong>@trans("titles.error")</strong><br>   
		@foreach($errors->all() as $error)
		{!! $error !!} 
	 	<br>
		@endforeach
	</p>
@endif  
@if(session()->has('success'))
	<p class="big-message green-gradient"> 
		<a  title="Hide message" class="close show-on-parent-hover">✕</a> 
		<span class="block-arrow"><span></span></span> 
		<span class="big-message-icon icon-ok"></span> 
		<strong>@trans("titles.success")</strong><br>   
		@foreach(collect(session('success')) as $message)
		{!! $message !!} 
	 	<br>
		@endforeach
	</p>
@endif 