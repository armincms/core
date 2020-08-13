<?php 
namespace Core\Menu\Forms;

use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Forms\ResourceForm;
use Core\Language\Concerns\HasLanguage; 

class MenuForm extends ResourceForm
{ 
	use HasPublishing, HasLanguage;

    public function build()
    {
        $this 
        	->languageSelection() 
        	->addPublishing('status', null, ['only' => ['activated', 'deactivated']])
        	->field('text', 'title', false, 'armin::title.title', [], null, ['required' => 'required'])
        	->field('text', 'description', false, 'armin::title.description');
    }

    public function generalMap()
    {
    	return [
    		'language', 'title', 'description', 'status'
    	];
    }

    public function relationMap()
    {
    	return [];
    }
}
