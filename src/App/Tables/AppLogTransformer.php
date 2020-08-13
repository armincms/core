<?php
namespace Core\App\Tables;

use League\Fractal\TransformerAbstract; 
use Core\App\AppLog;  

class AppLogTransformer extends TransformerAbstract
{  
    /**
     * @param \Core\AppLog\Models\AppLog $log
     * @return array
     */
    public function transform(AppLog $log)
    {   
        return [ 
            'status'    => $log->status? '<b class=green>online</b>' : '<span class=red>' .$log->updated_at->format('Y F D [ h:s:i ]'). '</span>', 
            'mobile'    => "<strong>{$log->mobile}</strong>&nbsp;[ <span class=blue>{$log->os}<small>&nbsp;{$log->os_version}</small></span> ]",   
            'app_version'=> $log->app_version, 
            'imei'=> $log->imei, 
            'city'      => $log->city, 
            'created_at'=> (string) $log->created_at->format('Y F D [ h:s:i ]'),      
        ];
    }   
}