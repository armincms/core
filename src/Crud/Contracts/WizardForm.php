<?php 
namespace Core\Crud\Contracts;  

interface WizardForm extends TabForm
{   
	public function setIcon(string $icon);
	public function getIcon();
}
