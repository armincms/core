<?php 
namespace Core\User\Http\Controllers;
 
use Illuminate\Http\Request;
use Core\Crud\Resource;  
use Core\Crud\Contracts\PublicatableResource;
use Core\Crud\Contracts\Compact;

abstract class Controller extends Resource implements PublicatableResource
{   

    protected $navigation = 'user-management';
 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource)
    { 
        $duplicated = $this->model()
            ->where('username', $request->get('username'))
            ->where('id', '!=', $resource->id)
            ->count();

        if($duplicated) { 
            return back()->withInput()->withErrors([
                'username' => 'Invalid Username.'
            ]);
        }

        if($this->checkPassword($request->input('user_password'))) {
            return parent::update($request, $resource); 
        } 

        return back()->withInput()->withErrors([
            'user_password' => 'Incorrect User Password.'
        ]);
    }


    protected function syncRelations($relations, &$resource)
    {  
        $resource->setMeta(data_get($relations, 'meta.sync'));
        $resource->save(); 
    }

    public function checkPassword($password = null)
    {
        return app('hash')->driver('bcrypt')->check(
            $password, \Auth::guard('admin')->user()->password
        );
    }

    public function columns()
    {
        return [
            'id' => [
                'title'      => armin_trans('armin::title.id'),
                'searchable' => true
            ],
            'fullname' => [
                'title'      => armin_trans('user-management::title.name'),
                'render' => 'function() {
                    return "<b>" + this.firstname +" "+ this.lastname 
                            + "</b></br><small class=red>" +this.displayname+ "</small>";
                }',
                'searchable' => true, 
                'search_callback' => function($q, $search) {
                    $q
                        ->where($q->qualifyColumn('firstname'), 'LIKE', "%{$search}%")
                        ->orWhere($q->qualifyColumn('lastname'), 'LIKE', "%{$search}%")
                        ->orWhere($q->qualifyColumn('displayname'),'LIKE', "%{$search}%");
                },
                'orderable' => true, 
                'order_callback' => function($q, $dir) {
                    $q
                        ->orderBy($q->qualifyColumn('displayname'), $dir)
                        ->orderBy($q->qualifyColumn('firstname'), $dir)
                        ->orderBy($q->qualifyColumn('lastname'), $dir);
                }
            ], 
            'username' => [
                'title'      => armin_trans('user-management::title.username'),
                'searchable' => true
            ],  
            'email' => [
                'title'      => armin_trans('user-management::title.email'),
                'searchable' => true
            ], 
        ];
    }   

    public function getDataTable()
    {  
        $model = $this->model(); 

        if($this->checkSoftDeletesOnModel($model)) {
            $model = $model->withTrashed(); 
        } 

        $query = $model->with($this->with)->withCount($this->withCount);

        if(\Auth::guard('admin')->user()->username !== 'superadministrator') {
            $query = $query->where('username', '!=', 'superadministrator');
        }

        return \DataTables::of($query); 
    }  

    public function getAvailableStatuses()
    {
        return ['pending', 'activated'];
    }  
    public function getStatusColumn()
    {
        return 'status';
    }   
}
