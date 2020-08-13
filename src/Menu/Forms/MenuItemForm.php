<?php 
namespace Core\Menu\Forms;

use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Forms\ResourceForm;
use Core\Language\Concerns\HasLanguage; 

class MenuItemForm extends ResourceForm
{ 
	use HasPublishing, HasLanguage;

    public function build()
    {
        $this 
        	->raw('<div class=columns></div>');
    }

    public function generalMap()
    {
    	return [
    		'language', 'title', 'description', 'site_id', 'status'
    	];
    }

    public function relationMap()
    {
    	return [];
    }
}
