<?php
namespace Core\Form; 

use Illuminate\Support\ServiceProvider;   
use Illuminate\Foundation\AliasLoader;   
use Form;

class FormServiceProvider extends ServiceProvider
{    

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {        
        require __DIR__.DS.'helpers.php';
        
        \Config::set('annisa.form.default_component', 'ad');

        app('router')->middleware(config('admin.panel.middleware', ['web', 'auth:admin']))
                     ->prefix(config('admin.panel.path_prefix', 'panel'))
                     ->namespace(__NAMESPACE__.'\Http\Controllers') 
                     ->group(__DIR__.DS.'routes.php'); 

        \Event::listen(\Core\Crud\Events\CoreServing::class, function() {
            $this->loadViewsFrom(__DIR__.'/resources/views', 'form');   
            $this->registerArminDeveloperComponents(); 
            (new BSFormComponentRegisterar)->register();
        });
    }


    function registerArminDeveloperComponents()
    {      
        $this->registerSelects();  
        $this->registerInputs();  
        $this->registerFileInpus();  
        $this->registerButtons();  
    } 
    function registerFileInpus()
    { 
        Form::component('adMultipleImage', 'form::components.multiple-image', [ 
            'name',
            'label' => null,  
            'accepted'  => [],
            'attributes'=> [],  
            'label_attributes'  => [],  
            'wrapper_attributes'=> [] 
        ]);
        Form::component('adFile', 'form::components.file', [ 
            'name',
            'label' => null,  
            'accepted'  => [],
            'multiple'  => false,
            'attributes'=> [],  
            'label_attributes'  => [],  
            'wrapper_attributes'=> [] 
        ]);
        Form::component('adLabeledFile', 'form::components.labeled-file', [ 
            'name',
            'label' => null,  
            'input_label' => null,  
            'accepted'  => [],
            'multiple'  => false,
            'attributes'=> [],  
            'label_attributes'  => [],  
            'input_label_attributes'  => [],  
            'wrapper_attributes'=> [] 
        ]);
    }

    function registerInputs()
    { 
        Form::component('adText', 'form::components.input', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'type' => 'text',
            'help' => null,
        ]); 
        Form::component('adEmail', 'form::components.input', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'type' => 'email',
            'help' => null,
        ]); 
        Form::component('adPassword', 'form::components.password', [ 
            'name',
            'label' => null,
            'value' => null,   
            'attributes' => [],  
            'label_attributes' => [],  
            'wrapper_attributes' => [], 
            'type' => 'password',
            'help' => null,
        ]); 
        Form::component('adLabeledText', 'form::components.labeled-input', [ 
            'name',
            'label' => null,
            'value' => null,   
            'input_label' => null,
            'attributes' => [],  
            'label_attributes' => [],  
            'input_label_attributes' => [], 
            'wrapper_attributes' => [], 
            'type' => 'text',
            'help' => null,
        ]); 
        Form::component('adInputSelect', 'form::components.input-select', [ 
            'name',
            'label' => null,
            'value' => null, 
            'input_label' => null,  
            'select'=> [],
            'attributes' => [],  
            'label_attributes' => [], 
            'input_label_attributes' => [], 
            'wrapper_attributes' => [], 
            'help' => null,
        ]); 
        Form::component('adSwitch', 'form::components.switch', [ 
            'name',
            'label' => null,
            'on'    => 1,  
            'off'   => 0, 
            'checked'   => 1, 
            'attributes'=> [],  
            'label_attributes'  => [],  
            'wrapper_attributes'=> [], 
            'help' => null,
        ]); 
        Form::component('adTextarea', 'form::components.textarea', [ 
            'name',
            'label' => null, 
            'value' => null, 
            'attributes'=> [],  
            'label_attributes'  => [],  
            'wrapper_attributes'=> [], 
            'help' => null,
        ]); 
    }
    function registerButtons()
    { 
        Form::component('adButton', 'form::components.button', [
            'name',
            'label' => null, 
            'icon'  => null, 
            'attributes' => [],  
            'wrapper_attributes'     => [],
            'help' => null,
        ]); 
        Form::component('adSave', 'form::components.buttons.icon-button', [
            'name',
            'label' => 'armin::action.save', 
            'icon'  => 'floppy', 
            'color' => 'green', 
            'attributes' => [],   
            'help' => null,
        ]); 
        Form::component('adRadioButtons', 'form::components.buttons.radio-buttons', [
            'name',
            'label' => 'armin::action.save',  
            'buttons'  => [],  
            'attributes' => [],   
            'label_attributes' => [],   
            'wrapper_attributes' => [],   
            'help' => null,
        ]); 
        Form::component('adGroupButtons', 'form::components.buttons.radio-buttons', [
            'name',
            'label' => 'armin::action.save',  
            'buttons'  => [],  
            'attributes' => [],   
            'label_attributes' => [],   
            'wrapper_attributes' => [],   
            'help' => null,
        ]); 
    }

    function registerSelects()
    { 
        Form::component('adSelect', 'form::components.select', [
            'name',
            'label'     => null,
            'values'    => [],
            'selected'  => [],
            'attributes'=> [],
            'label_attributes'       => [],
            'options_attributes'     => [],
            'optiongroups_attributes'=> [],
            'wrapper_attributes'     => [],
        ]); 
    }
     

}
