<?php

namespace Core\App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Storage;

class AppVersionController extends Controller
{
    protected $versions;

    function __construct()
    {
        $this->versions = armin_setting("app.version.". request('os')); 
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function lastVersion(Request $request, $os)
    {
        $item = $this->getLastAvalableVersion($os);

        $version = (int) array_get($item, 'version_name', 0); 
        $filePath= trim(array_get($item, 'path'));
        $path    = Storage::disk('armin.file')->url($filePath ?? 'none.none');
        
        return compact('version', 'path');
    } 
    
    function getLastAvalableVersion($os)
    {
        if($item = $this->versions->where('active_version', 1)->first()) {
            return $item;
        } 

        $max = $this->versions->max('version_name');

        return $this->versions->where('version_name', $max)->pop();
        
    }
     
}
