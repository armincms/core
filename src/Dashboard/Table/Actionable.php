<?php
namespace Core\Dashboard\Table; 


trait Actionable
{ 
	
	public function addActions(array $actions = [])
	{ 
		$actions = collect($actions)->map(function($data, $action) {
			if($this->hasMutator($action)) {
				return $this->callMutator($action, $data);
			}

			return $this->link(
				array_get($data, 'href', 'javascript:void(0);'), array_except($data, 'href')
			);
		})->implode(''); 

		return "<span class=button-group>{$actions}</span>";
	}

	public function hasMutator($action)
	{
		return method_exists($this, $this->getMutator($action));
	}

	public function getMutator($action)
	{
		return "action".studly_case($action);
	}

	public function callMutator($action, $data)
	{
		$method = $this->getMutator($action);

		return $this->{$method}($data);
	}

	public function actionEdit($path)
	{
		return $this->link($path, [
			'class' => 'button with-tooltip glossy blue-gradient icon-pencil',
			'title' => trans('actions.edit'), 
		]);
	}

	public function actionView($path)
	{
		return $this->link($path, [
			'class'	=> 'button with-tooltip glossy orange-gradient icon-eye', 
			'target'=> 'view', 
			'title' => trans('actions.view'), 
		]);
	}

	public function actionDelete($path)
	{
		return $this->link($path, [
			'class' => 'button with-tooltip glossy icon-trash red-gradient destroy-content',
			'title' => trans('actions.delete'), 
			'data-confirm-options' => json_encode([
				'message' => trans("actions.confirm")
			])
		]);
	}

	protected function link($path, array $attributes)
	{
		return "<a href={$path}" .\Html::attributes($attributes). "></a>";
	}

}