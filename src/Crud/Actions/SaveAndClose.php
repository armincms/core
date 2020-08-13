<?php 
namespace Core\Crud\Actions; 


class SaveAndClose Extends Action
{ 
	protected function action()
	{
		return 'save&close'; 
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
		return 'admin-crud::action.save&close';
	}
}