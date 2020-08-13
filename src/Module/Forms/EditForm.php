<?php 
namespace Core\Module\Forms;

use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Contracts\TabForm; 
use Core\Crud\Forms\ResourceForm;
use Core\Language\Concerns\HasLanguage; 

class EditForm extends ResourceForm implements TabForm
{ 
	use HasPublishing, HasLanguage;

    public function build()
    {
        $this->child('display-position', function($form) {
            $this->displayPosition($form);
        })
        ->pushScript('selection-script', view('module::selection-script', ['module' => $this->model]));
    }

    public function displayPosition($form)
    {

        $form 
            ->raw('<div class=columns><div class=four-columns>')
            ->field('checkable', 'show_on', false, 'module::title.display_position', [
                '*' => [
                    'value' => '*',
                    'label' => 'module::title.all',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ],
                'selection' => [
                    'value' => 'selection',
                    'label' => 'module::title.selection',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ],
                'rejection' => [
                    'value' => 'rejection',
                    'label' => 'module::title.rejection',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ]
            ], optional($this->model)->show_on?: '*', true)
            ->raw('</div><div class=four-columns>')
            ->field('select', 'language', false, 'language::title.language', language()->filter(function($language, $active) { return !$active || $language->status; }
            )->pluck('title', 'alias')->prepend(armin_trans('module::title.all'), '*')) 
            ->element('hidden', 'locate[]')
            ->raw('</div><div class="twelve-columns hidden" id=selection></div></div>');
        
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
