<?php 
namespace Core\Crud\Events;

 
trait AccessibleEvent
{ 

	public function checkPermission()
	{ 
		$this->owner->canAndOwns($this->permissionName(), $this->resource); 
	}

	public function permissionName()
	{
		return $this->action() .'-'. $this->permission();
	}

	public function permission()
	{
		return class_basename($this->resource);
	}

	public function action()
	{
		$class = str_slug(class_basename($this->resource)); 

		return trim(preg_replace('/-event/', '', $class), '-');
	}
}