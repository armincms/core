<?php
namespace Core\Crud\Tables; 


trait HasAction
{ 
	
	public function addActions(array $actions = [])
	{ 
		$actions = collect($actions)->map(function($data, $action) {
			if($this->hasMutator($action)) { 
				return $this->callMutator($action, $data);
			}

			return $this->link(
				array_get($data, 'href', 'javascript:void(0);'), 
				array_except($data, 'href')
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

	public function actionEdit($data)
	{
		return $this->link($this->getLink($data), [
			'class' => 'button with-tooltip glossy blue-gradient icon-pencil',
			'title' => armin_trans('admin-crud::action.edit_resource'), 
		]);
	}

	public function actionView($data)
	{
		return $this->link($this->getLink($data), [
			'class'	=> 'button with-tooltip glossy orange-gradient icon-eye', 
			'target'=> 'view', 
			'title' => armin_trans('admin-crud::action.view_resource'), 
		]);
	}

	public function actionDelete($data)
	{
		return $this->link($this->getLink($data), [
			'class' => 'button with-tooltip glossy icon-trash red-gradient confirm-resource',
			'title' => armin_trans('admin-crud::action.delete_resource'),
			'data-input' => json_encode(['_method' => 'delete']),
			'data-confirm-options' => json_encode([
				'message' => armin_trans("admin-crud::confirm.delete_resource")
			])
		]);
	}

    public function actionDestroy($data)
    {
        return $this->link($this->getLink($data), [
            'class' => 'button with-tooltip glossy icon-trash-empty red-gradient confirm-resource',
            'title' => armin_trans('admin-crud::action.destroy_resource'), 
			'data-input' => json_encode(['_method' => 'delete']),
            'data-confirm-options' => json_encode([
                'message' => armin_trans("admin-crud::confirm.destroy_resource")
            ])
        ]);
    }


    public function actionRestore($data)
    {
        return $this->link($this->getLink($data), [
            'class' => 'button with-tooltip glossy icon-recycle green-gradient confirm-resource',
            'title' => armin_trans('admin-crud::action.restore_resource'), 
            'data-confirm-options' => json_encode([
                'message' => armin_trans("admin-crud::confirm.restore_resource")
            ])
        ]);
    } 

    protected function getLink($data)
    {
    	if(is_string($data)) {
    		return $data;
    	}

    	return array_get($data, 'href', 'javascript:void(0);');
    }

	protected function link($path, array $attributes, $content = '')
	{
		array_except($attributes, 'resource');
		
		return \Html::tag('a', $content, $attributes + ['href' => $path])->toHtml();
	}

}