<?php 
namespace Core\Crud; 
 
use Illuminate\Http\Request; 
use Yajra\DataTables\Html\Builder; 
use DataTables;

abstract class CompactResource extends Resource 
{ 
    protected $view = 'admin-crud::compact-edit';  

    protected $resource;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $builder)
    {   
    	if (request()->ajax()) {
    		return $this->getAjaxTable();
	    }

	    $table = $this->getTable($builder); 

        if($this instanceof Contracts\PublicatableResource) {  
            $table = $this->addPublicationColumn($table);
        }  

        return view($this->view)->nest(
            'table', 'admin-crud::datatables.html-table', compact('table')
        )
        ->withName($this->name())
        ->withTitle($this->title())
        ->withForm(optional($this->form())->setModel($this->resource))
        ->withActions($this->getActions());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->index(request(), app(Builder::class));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $resource)
    {
        editing_resource($resource->load($this->with), $request->user()); 

        $this->resource = $resource;  

        return $this->index($request, app(Builder::class));
    } 
} 