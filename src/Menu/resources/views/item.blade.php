<li>
	@if($childs)
	<i class='updown'></i>
	@endif
    <input type=checkbox role=menu-item class=chck name={{ $group }}-item[]
		 data-title='{{ array_get($item, 'title') }}'
		 data-url='{{ array_get($item, 'url', 'javascript:void(0);') }}' 
		 data-type="{{ array_get($item, 'type') }}" 
		 data-id='{{ array_get($item, 'id') }}'  
    >
    <span>
    	{{ array_get($item, 'title') }}
    </span> 
    @if($childs)<ul>{!! $childs !!}</ul>@endif
</li>  