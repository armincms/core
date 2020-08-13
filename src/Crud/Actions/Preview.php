<?php 
namespace Core\Crud\Actions; 


class Preview Extends Action
{ 
	public function __construct($url)
	{
		$this->url = $url;
	}


	public function toHtml()
	{  
		return \Html::tag('a', $this->buttonIcon(). $this->buttonTitle(), [
			'class' => 'button with-tooltip margin-left white-gradient',
			'href'  => $this->url,
			'target' => '_blank'  
		])->toHtml();
	}

	protected function action()
	{
		return 'preview'; 
	}
	protected function type()
	{
		return 'link'; 
	}
	protected function icon()
	{
		return 'eye';
	}
	protected function color()
	{
		return 'blue';
	}
	protected function title()
	{
		return 'admin-crud::action.preview';
	}
}