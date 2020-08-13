<?php  
namespace Core\App\Http\Controllers; 
 
use App\Http\Controllers\Controller;   
use Core\App\Repository\AppPageRepository; 
use Illuminate\Http\Request;

class AppVersionController extends Controller
{ 
    protected $items;

    function __construct()
    {
        $this->os = request('os');

        $items = json_decode(armin_setting("app.version.{$this->os}", '[]'), true);

        $this->versions = collect($items);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('app::version.index')
                            ->withVersions($this->versions)
                            ->withVersion(null)
                            ->withOs($this->os);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $item = $this->fetchData($request);

        $item['path'] = $this->fetchAppFile(
                            $request->file('app_file'), $item['version'], $item['path']
                        );  

        if(array_get($item, 'active_version')) {
            $this->cleanActiveVersions();
        }

        $this->versions->put($item['version'], $item);

        $this->save();

        return $this->redirect($request->next_action, $item['version']); 
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Armin\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function edit($os, $appVersion)
    {     
        return view('app::version.index')
                        ->withVersions($this->versions)
                        ->withVersion($this->versions->get($appVersion))
                        ->withOs($this->os);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Armin\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $os, $appVersion)
    {  
        $item = array_merge(
            $this->versions->pull($appVersion), $this->fetchData($request)
        );  

        array_set($item, 'path',
            $this->fetchAppFile($request->file('app_file'), $item['version'], $item['path']) 
        ); 

        if(array_get($item, 'active_version')) {
            $this->cleanActiveVersions();
        }

        $this->versions->put($appVersion, $item);

        $this->save();

        return $this->redirect($request->next_action, $item['version']); 
    }

    function fetchData($request)
    { 
        $data['version']= $request->version;
        $data['os']     = $this->os; 
        $data['path']   = $request->path;
        $data['version_name']   = $request->get('version_name', 1); 
        $data['active_version'] = $request->get('active_version', 0);  

        return $data;
    }

    function fetchAppFile($file, $version, $old)
    {  
        if(isset($file) && $file->isValid()) {  
            return $file->storeAs(
                "apps/{$this->os}/{$version}", $file->getClientOriginalName(), 'armin.file'
            ); 
        } 

        return $old;
    }

    function cleanActiveVersions()
    {
        $this->versions = $this->versions->map(function($value){
            $value['active_version'] = 0;

            return $value;
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function destroy($os, $appVersion)
    {
        $item = $this->versions->pull($appVersion); 
 
        \Storage::disk('armin.file')->deleteDirectory("apps/{$os}/{$appVersion}"); 

        $this->save();

        return $this->redirect();
    } 

    function save()
    { 
        armin_setting(["app.version.{$this->os}" => $this->versions->toJson()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Armin\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function redirect($action = null, string $id = null)
    {
        switch ($action) {
            case 'save':
                return redirect()->route('{os}.edit', [$this->os, $id])->withMsg(1);
                break;
            
            default:
                return redirect()->route('{os}.index', $this->os)->withMsg(1);
                break;
        }
    } 
}
