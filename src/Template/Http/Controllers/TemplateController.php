<?php 
namespace Core\Template\Http\Controllers;
 
use Core\Template\Tables\TemplateTransformer;
use Illuminate\Http\Request; 
use Core\Template\Template;
use Core\Template\Forms\TemplateForm;
use Core\Crud\Resource;
use DataTables;

class TemplateController extends Resource
{   
    public $navigation = 'extension'; 

    public function name()
    {
        return 'template';
    }

    public function title()
    {
        return 'template::title.templates';
    }

    public function columns()
    {
        return [
            'name' => [
                'title' => armin_trans('template::title.name')
            ],
            'title' => [
                'title' => armin_trans('template::title.title')
            ],
        ];
    }  

    public function model()
    {
        return app('template');
    } 

    public function form()
    {
        return new TemplateForm;
    } 


    public function getDataTable()
    {      
        return DataTables::of(app('template.repository')->all()); 
    } 

    protected function getTableTransformer()
    {
        return new TemplateTransformer($this);
    }
    
    public function defaultColumnOrdering($q, $column, $direction)
    {  
        return $q->collection->sortBy($column, SORT_REGULAR, $direction === 'asc');

    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource)
    {    
        $this->form()->setModel($resource)->save(function() {});
        
        return $this->redirect($request, $resource); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $resource)
    {  
        return view($this->editView)
                        ->withForm($this->form()->setModel($resource))
                        ->withName($this->name())
                        ->withTitle($this->title())
                        ->withActions($this->getActions())
                        ->withRouteParameters(
                            (array) $this->routeParameters('create', $resource)
                        );
    } 

    public function default(Request $request, $template)
    {
        default_template($template->name());

        return []; 
    }

    protected function defaultActions()
    {
        $actions = parent::defaultActions();

        unset($actions[1]);

        return $actions; 
    }

    public function routes($router)
    {
        $router->post('{template}/default', 'TemplateController@default')->name('default');
    } 
}
