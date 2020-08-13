<?php  
namespace Core\App\Http\Controllers; 
 
use App\Http\Controllers\Controller; 
use Core\App\Repository\AppLogRepository; 
use Core\App\Tables\AppLogDataTable; 
use Illuminate\Http\Request;

class AppLogController extends Controller
{
    protected $repository;

    function __construct(AppLogRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AppLogDataTable $builder)
    {  
        if (request()->ajax()) {
            return $builder->ajax();
        }

        $table = $builder->html(); 

        return view('app::log.index')
                        ->nest('table', 'dashboard::datatables.html-table', compact('table'))
                        ->withLogs($this->repository->get()->load([
                            'metas' => function($q) {
                                $q->where('key', 'city');
                            }
                        ]))
                        ->withLog(null);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $log = $this->repository->create($request->only(['title', 'full_text', 'image']));
 
        if($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path   = $request->file('image_file')->store("app/logs/{$log->id}", 'armin.image');
            $image  = array_merge($log->image->toArray(), compact('path'));

            $this->repository->update(compact('image'), $log->id);
        } 

        return $this->redirect($request->next_action, $log->id); 
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Armin\AppLog  $appLog
     * @return \Illuminate\Http\Response
     */
    public function edit($appLog)
    {
        $logs = $this->repository->get();

        return view('app::log.index')
                    ->withLogs($logs)
                    ->withLog($logs->find($appLog));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armin\AppLog  $appLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $appLog)
    {
        $data = $request->only(['title', 'full_text', 'image']);

        if($request->hasFile('image_file') && $request->file('image_file')->isValid()) { 
            array_set(
                $data, 
                'image.path', 
                $request->file('image_file')->store("app/logs/{$appLog}", 'armin.image')
            ); 
        } 

        $log = $this->repository->update($data, $appLog);

        return $this->redirect($request->next_action, $log->id); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppLog  $appLog
     * @return \Illuminate\Http\Response
     */
    public function destroy($appLog)
    {
        $this->repository->delete($appLog);

        return $this->redirect();
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppLog  $appLog
     * @return \Illuminate\Http\Response
     */
    public function redirect($action = null, string $id = null)
    {
        switch ($action) {
            case 'save':
                return redirect()->route('app-log.edit', [$id])->withMsg(1);
                break;
            
            default:
                return redirect()->route('app-log.index')->withMsg(1);
                break;
        }
    } 
}
