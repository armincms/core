<?php 
namespace Core\Crud\Actions; 


class SaveAndNew Extends Action
{ 
	protected function action()
	{
		return 'save&new'; 
	}
	protected function type()
	{
		return 'submit'; 
	}
	protected function icon()
	{
		return 'floppy';
	}
	protected function color()
	{
		return 'green';
	}
	protected function title()
	{
		return 'admin-crud::action.save&new';
	}


	public function toInlineHtml()
	{ 
		return \Html::tag('button', $this->spanIcon()."<span class='icon-plus'></span>", [
			'class' => "button with-tooltip orange-gradient",
			'type'  => $this->type(),
			'value' => $this->action(),
			'name'  => $this->actionName(),
			'title' => armin_trans($this->title()),
		])->toHtml();
	}
}