<?php  
namespace Core\App\Http\Controllers; 
 
use App\Http\Controllers\Controller;   
use Core\App\Repository\AppPageRepository; 
use Illuminate\Http\Request;

class AppMenuController extends Controller
{ 
    protected $menus;

    function __construct(AppPageRepository $repository)
    {
        $this->repository = $repository;

        $menus = json_decode(armin_setting('app.menu', '[]'), true);

        $this->menus = collect($menus);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('app::menu.index')
                            ->withMenus($this->menus)
                            ->withMenu(null)
                            ->withPages($this->repository->get());
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $menu = [
            'id' => time(),
            'page'  => $request->page,
            'icon'  => $request->icon,
            'order' => (int) $request->order,
        ];

 
        if($request->hasFile('icon_file') && $request->file('icon_file')->isValid()) {
            $menu['icon']   = $request->file('icon_file')->store("app/menus/{$menu['id']}", 'armin.image'); 
        } 

        $this->menus->put($menu['id'], $menu);

        $this->save();

        return $this->redirect($request->next_action, $menu['id']); 
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Armin\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function edit($appMenu)
    {   
        return view('app::menu.index')
                        ->withMenus($this->menus)
                        ->withMenu($this->menus->get($appMenu))
                        ->withPages($this->repository->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armin\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $appMenu)
    {
        $menu = $this->menus->pull($appMenu);

        $menu['id']     = $appMenu;
        $menu['page']   = $request->page;
        $menu['icon']   = $request->icon;
        $menu['order']  = (int) $request->order; 

 
        if($request->hasFile('icon_file') && $request->file('icon_file')->isValid()) {
            $menu['icon']   = $request->file('icon_file')->store("app/menus/{$menu['id']}", 'armin.image'); 
        } 

        $this->menus->put($appMenu, $menu);

        $this->save();

        return $this->redirect($request->next_action, $menu['id']); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy($appMenu)
    {
        $this->menus->pull($appMenu);

        $this->save();

        return $this->redirect();
    } 

    function save()
    { 
        armin_setting(['app.menu' => $this->menus->toJson()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function redirect($action = null, string $id = null)
    {
        switch ($action) {
            case 'save':
                return redirect()->route('app-menu.edit', [$id])->withMsg(1);
                break;
            
            default:
                return redirect()->route('app-menu.index')->withMsg(1);
                break;
        }
    } 
}
