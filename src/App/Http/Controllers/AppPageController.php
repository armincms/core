<?php  
namespace Core\App\Http\Controllers; 
 
use App\Http\Controllers\Controller; 
use Core\App\Repository\AppPageRepository; 
use Illuminate\Http\Request;

class AppPageController extends Controller
{
    protected $repository;

    function __construct(AppPageRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app::page.index')->withPages($this->repository->get())->withPage(null);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = $this->repository->create($request->only(['title', 'full_text', 'image']));
 
        if($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path   = $request->file('image_file')->store("app/pages/{$page->id}", 'armin.image');
            $image  = array_merge($page->image->toArray(), compact('path'));

            $this->repository->update(compact('image'), $page->id);
        } 

        return $this->redirect($request->next_action, $page->id); 
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Armin\AppPage  $appPage
     * @return \Illuminate\Http\Response
     */
    public function edit($appPage)
    {
        $pages = $this->repository->get();

        return view('app::page.index')
                    ->withPages($pages)
                    ->withPage($pages->find($appPage));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armin\AppPage  $appPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $appPage)
    {
        $data = $request->only(['title', 'full_text', 'image']);

        if($request->hasFile('image_file') && $request->file('image_file')->isValid()) { 
            array_set(
                $data, 
                'image.path', 
                $request->file('image_file')->store("app/pages/{$appPage}", 'armin.image')
            ); 
        } 

        $page = $this->repository->update($data, $appPage);

        return $this->redirect($request->next_action, $page->id); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppPage  $appPage
     * @return \Illuminate\Http\Response
     */
    public function destroy($appPage)
    {
        $this->repository->delete($appPage);

        return $this->redirect();
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppPage  $appPage
     * @return \Illuminate\Http\Response
     */
    public function redirect($action = null, string $id = null)
    {
        switch ($action) {
            case 'save':
                return redirect()->route('app-page.edit', [$id])->withMsg(1);
                break;
            
            default:
                return redirect()->route('app-page.index')->withMsg(1);
                break;
        }
    } 
}
