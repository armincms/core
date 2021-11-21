<?php

namespace Core\Module\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Crud\Resource;
use Core\Module\ModuleInstance as Module;
use Core\Module\Forms\ModuleForm;
use Core\Module\Tables\ModuleTransformer;
use Core\Crud\Contracts\PublicatableResource;

class ModuleController extends Resource implements PublicatableResource
{    

    protected $indexView= 'module::.index';
    protected $editView = 'module::.edit';

    public function name()
    {
        return 'module';
    }
    public function title()
    {
        return 'module::title.modules';
    }  

    public function columns()
    {
        return [
            'id' => [
                'title'      => armin_trans('armin::title.id'),
                'searchable' => true
            ],
            'title' => [
                'title'         => armin_trans('module::title.title'),
                'searchable'    => true,
                'orderable'     => true,
                'multilingual'  => true 
            ], 
            'module' => [
                'title'      => armin_trans('module::title.module'),
                'searchable'    => true, 
                'orderable'     => true,
            ],
            'position' => [
                'title'      => armin_trans('module::title.position'),
                'orderable'     => true,
                'searchable'    => false, 
            ],
        ];
    }  
    public function model()
    {
        return new Module;
    } 
    public function form()
    {
        return new ModuleForm;
    } 

    protected function getTableTransformer()
    {
        return new ModuleTransformer($this);
    } 

    public function getAvailableStatuses()
    {
        return ['draft', 'published', 'scheduled'];
    }  
    public function getStatusColumn()
    {
        return 'status';
    }  

    public function routes($router)
    { 
        $router->get('create/{instance}', 'ModuleController@create')->name('create'); 
        //for update blog by name
        $router->post('{module}/refresh', 'InstanceController@refresh')->name('instance.refresh'); 
        //for update blog by name
        $router->get('{id}/status/{status}', 'ModuleController@modifierStatus'); 
        //group module  
        $router->post('/{activation}/activation', 'ModuleController@groupActivation' )->name('group-activation');
        $router->post('/delete', 'ModuleController@groupDelete' )->name('group-deletion');

        $router->post('/copy', 'ModuleController@groupCopy')->name('group-copy'); 

        $router->get('{module}/copy', 'ModuleController@copy')->name('copy'); 
        $router->get('selection/{module?}', 'SelectionController@handle')->name('selection');
    }

    public function redirect($request, $resource=null, $messages = [])
    {
        if($request->ajax()) {
            return $resource;
        }
        
        $action = $request->get('_action'); 

        if(isset($resource) && $action == 'save') {
            return redirect()->route($this->name(). '.edit', $resource)->withSuccess(
                $messages + [armin_trans('successfully_saved')]
            );
        }
        
        if($action == 'save&new') { 
            return redirect()->route($this->name(). '.create', $resource->module)->withSuccess(
                $messages + [armin_trans('successfully_saved')]
            );
        }

        if($action != 'close') {
            $messages = array_push($messages, armin_trans('successfully_saved'));
        }

        return redirect()->route($this->name(). '.index')->withMessages($messages);
    }
}
