<?php 
namespace Core\Menu\Http\Controllers;

use Core\Crud\Contracts\PublicatableResource;
use Core\Crud\Resource;
use Core\Menu\Menu;
use Core\Menu\MenuItem;
use Core\Menu\Forms\MenuForm;
use Core\Menu\Forms\MenuItemForm;
use Core\Menu\Tables\MenuTransformer;
use Illuminate\Http\Request;


class MenuController extends Resource implements PublicatableResource
{
	protected $with = [];

	public function name()
	{
		return 'menu';
	}

	public function title()
	{
		return 'menu::title.menu';
	}

	public function model()
	{
		return new Menu;
	}

	public function columns()
	{
		return [
			'id' => [
				'title' => armin_trans('armin::title.id')
			],
			'title' => [
				'title' => armin_trans('armin::title.title')
			], 
			'language' => [
				'title' => armin_trans('language::title.language'),
				'width' => 35
			]
		];
	}

	public function form()
	{
		return new MenuForm;
	}

	public function getAvailableStatuses()
	{
		return ['activated', 'deactivated'];
	}

	public function getStatusColumn()
	{
		return 'status';
	}

    protected function getTableTransformer()
    {
        return new MenuTransformer($this);
    }

    public function routes($router)
    { 
    	$router->get('{menu}/item', 'MenuController@editItems')->name('item.edit'); 
    	$router->post('{menu}/item', 'MenuController@storeItems')->name('item.store');
    }

    public function editItems(Request $request, $resource)
    {    
    	$resource->load(['items' => function($q) {
    		$q->whereNull('menu_item_id');
    	}]);

        return view('menu::add-item', compact('resource'))
                        ->withForm((new MenuItemForm())->setModel($resource))
                        ->withName('menu')
                        ->withTitle('menu::title.menu_item')
                        ->withResource($resource)
                        ->withMenuables(collect(config('menu.menuables')))
                        ->withRouteParameters([$resource])
                        ->withActions(collect([ 
				            new \Core\Crud\Actions\Save(), 
				            new \Core\Crud\Actions\SaveAndClose(),
				            new \Core\Crud\Actions\Close(route($this->name(). '.index')),
                        ]));
    }

    public function storeItems(Request $request, $resource)
    {   
    	$menus = collect($request->get('menu')); 
    	$menus->shift();
    	$map = [];
 
    	$resource->items()->get()->map->delete(); 

    	$menus->map(function($item) use (&$map, $resource) { 
    		if(isset($item['menu_item_id'])) {
    			$item['menu_item_id'] = array_get($map, $item['menu_item_id']);
    		} else {
    			$item['menu_item_id'] = null;
    		} 
    		
    		$item['menu_id'] = $resource->id;
    		$menu = $resource->items()->create(array_except($item, 'id')); 

    		$map[$item['id']] = $menu->id;

    		return $menu;
    	});  

    	if($request->_action === 'save&close') {
    		return $this->redirect($request);
    	} 

        return back()->withMessages([armin_trans('successfully_saved')]);
    } 

}
