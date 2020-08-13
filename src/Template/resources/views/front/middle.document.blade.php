@component('template::front.section', ['section' => 'middle'])  
	@component('template::front.container', [
		'section' => 'middle', 'width' => $this->containerWidth('middle')
	])

		@if($this->sectionIsActive('leftside') && ! empty($this->renderedModules('leftside')))
			@component('template::front.section', [
				'section' 	=> 'leftside',
				'sectionTag'=> 'aside',
				'width' => $this->leftsideGrades()->implode(' '),
			])
				{!! $this->renderedModules('leftside') !!}
			@endcomponent
		@endif

		@component('template::front.container', [
			'section' => 'mainwrapper', 
			'sectionTag' => 'div',
			'width' => $this->mainGrades()->implode(' '),
		]) 
			@component('template::front.section', [
				'section' => 'breadcrumb', 
				'sectionTag' => 'nav',
				'width' => '',
			])
				{!! $this->renderedModules('breadcrumb') !!}
			@endcomponent

			@component('template::front.section', [
				'section' => 'mainheader', 
				'sectionTag' => 'header',
				'width' => '',
			])
				{!! $this->renderedModules('mainheader') !!}
			@endcomponent

			@component('template::front.section', [
				'section' => 'main', 
				'sectionTag' => 'main',
				'width' => '',
			])
				{!! $this->renderedModules('main') !!}

				{!! $this->content() !!} 

			@endcomponent  

			@component('template::front.section', [
				'section' => 'mainfooter', 
				'sectionTag' => 'footer',
				'width' => '',
			])
				{!! $this->renderedModules('mainfooter') !!}
			@endcomponent

		@endcomponent

		@if($this->sectionIsActive('rightside') && ! empty($this->renderedModules('breadcrumb')))
			@component('template::front.section', [
				'section' 	=> 'rightside',
				'sectionTag'=> 'aside',
				'width' 	=> $this->rightsideGrades()->implode(' '),
			])
				{!! $this->renderedModules('rightside') !!}
			@endcomponent
		@endif 
	@endcomponent
@endcomponent