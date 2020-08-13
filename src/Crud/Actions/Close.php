<?php 
namespace Core\Crud\Actions; 


class Close Extends Action
{ 
	public function __construct($url)
	{
		$this->url = $url;
	}


	public function toHtml()
	{  
		return \Html::tag('a', $this->buttonIcon(). $this->buttonTitle(), [
			'class' => 'button with-tooltip margin-left white-gradient',
			'href'  => $this->url  
		])->toHtml();
	}

	public function toInlineHtml()
	{  
		return \Html::tag('a', $this->spanIcon(), [
			'class' => "button with-tooltip {$this->color()}-gradient",
			'href'  => $this->url,
			'title' =>   $this->buttonTitle()
		])->toHtml();
	}

	protected function action()
	{
		return 'close'; 
	}
	protected function type()
	{
		return 'link'; 
	}
	protected function icon()
	{
		return 'cancel';
	}
	protected function color()
	{
		return 'red';
	}
	protected function title()
	{
		return 'admin-crud::action.close';
	}
}