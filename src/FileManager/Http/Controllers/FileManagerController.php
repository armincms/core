<?php 
namespace Core\FileManager\Http\Controllers;
 

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request; 
use Storage;

class FileManagerController extends Controller 
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests; 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {      
        $disk   = $request->disk ?? 'armin.file';
        $dir    = $request->directory;
        $contents = collect(Storage::disk($disk)->listContents($dir))->filter(
            function($item) use ($request) {
                if($request->type) {
                    return $item['type'] === $request->type;
                }

                return true;
            })->map(function($data) use ($disk) {  
                return $this->normalizeInfo($data, $disk);
            });

        $breadcrumbs = $this->getBreadCrumbs($dir);
        $directories = $this->getParentChild('/', $disk); 

        if($parent = $this->getParentDirectory($dir)) {
            $contents->prepend($parent);
        } 

        if($request->ajax()) {
            return compact('contents', 'breadcrumbs', 'directories');
        } 

        return view('file-manager::index');  
    }

    public function store(Request $request)
    {
        $uploaded   = collect();
        $directory  = $request->directory ?? now()->format('Y/m');
        $disk   = $request->disk ?? 'armin.file';

        foreach ((array) $request->file('files') as $file) {
            $uploaded->push($file->store($directory, $disk)); 
        }

        $contents = Storage::disk($disk)->listContents($directory);

        $contents = collect($contents)->filter(function($file) use ($uploaded) {  
            return $uploaded->search($file['path']) !== false;
        })->values()->map(function($data) use ($disk) {  
            return $this->normalizeInfo($data, $disk);
        }); 

        return compact('contents', 'directory');
    }

    public function getParentDirectory($directory)
    {
        preg_match_all('/[^\/\\\\]+/', $directory, $matches);

        if(count($matches[0])) {
            $directories = (array) $matches[0];

            array_pop($directories);

            return [
                'basename'  => "up",
                'dirname'   => "",
                'filename'  => "close",
                'path'  => implode('/', $directories) ?? '/',
                'type'  => "dir", 
                'up'    => true
            ]; 
        }  

        return null;
    } 

    public function getBreadCrumbs($dir)
    { 
        $parts = $this->getPathParts($dir);

        $breadcrumbs = [];
        $path = null;

        while ($part = array_shift($parts)) {
            $breadcrumbs[$part] = $path .= "/{$part}";
        }

        return $breadcrumbs;  
    }

    public function getParentChild($path = '/', $disk = 'armin.file', $depth = 0)
    { 
        return collect(Storage::disk($disk)->listContents($path))->filter(
            function($item) {
                return $item['type'] == 'dir';
            }
        )->map(function($item) use ($depth, $disk) {
            $parts = $this->getPathParts($item['dirname']);

            array_pop($parts);

            $item['parent'] = implode('/', $parts) ?: null;
            $item['childs'] = $this->getParentChild($item['path'], $disk, $depth + 1);
            $item['depth']  = $depth;

            return $item;
        });
        
    }

    public function getPathParts($dir)
    {
        preg_match_all('/[^\/\\\\]+/', $dir, $matches);

        return $matches[0]; 
    } 

    public function normalizeInfo($item, $disk)
    { 
        $item['url']    = Storage::disk($disk)->url($item['path']);
        $item['fullUrl']= url($item['url']); 
        $item['hash']   = md5($item['url']); 
        
        return $item;
    }

    public function destroy(Request $request, $fileName)
    {
        $path = "{$request->directory}/{$fileName}";
        $item = Storage::disk($disk = $request->get('disk', 'armin.file'))->getMetadata($path);

        Storage::disk($disk)->delete($path); 

        return $this->normalizeInfo($item, $disk);
    }
 
} 