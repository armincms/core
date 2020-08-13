@var($id = array_get($item, 'id'))
@var($title = array_get($item, 'title'))
@var($name = array_get($item, 'name', $name)) 
<li> 
	@if($childs) <i class="updown"></i> @endif
	
	<input type="checkbox" id="{{ $id }}"  value="1"  class="chck @if(empty($id)) hidden @endif"   
		name="locate[{{ $name }}][{{ $id }}]"   
		@if(optional($module)->locatedInItem($name, $id ?: '*'))
			checked  
		@endif 
	>
	<span>
		@if($title instanceof Illuminate\Support\HtmlString){!! $title->toHtml() !!}
		@else @trans($title)
		@endif
	</span> 
	@if($url = array_get($item, 'url'))
	<a href="{{ $url }}" class="icon-eye" target="preview"></a>
	@endif
	@if($childs)  
		<ul class="margin-right">{!! $childs !!}</ul>
	@endif
</li>  