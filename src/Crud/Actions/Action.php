<?php 
namespace Core\Crud\Actions; 


abstract class Action
{
	protected $type = 'submit';

	protected $icon = 'floppy';

	protected $color = 'green'; 

	protected $title = 'actions.save'; 


	public function toHtml()
	{ 
		return \Html::tag('button', $this->buttonIcon(). $this->buttonTitle(), [
			'class' => 'button with-tooltip margin-left white-gradient',
			'type'  => $this->type(),
			'value' => $this->action(),
			'name'  => $this->actionName(),
		])->toHtml();
	}

	public function toInlineHtml()
	{ 
		return \Html::tag('button', $this->spanIcon(), [
			'class' => "button with-tooltip {$this->color()}-gradient",
			'type'  => $this->type(),
			'value' => $this->action(),
			'name'  => $this->actionName(),
			'title' => armin_trans($this->title()),
		])->toHtml();
	}

	public function actionName()
	{
		return '_action';
	} 

	protected function buttonIcon()
	{
		return \Html::tag('span', $this->spanIcon(), [
			'class' => "button-icon glossy right-side {$this->color()}-gradient"
		])->toHtml();
	}

	public function spanIcon()
	{
		return "<span class='icon-{$this->icon()}'></span>";
	}

	protected function buttonTitle()
	{
		return armin_trans($this->title());
	}


	abstract protected function type();
	abstract protected function icon();
	abstract protected function color();
	abstract protected function title();
	abstract protected function action();

	public function __toString()
	{
		return $this->toHml();
	}
}