<?php

namespace Core\App\Http\Controllers\Api;

use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Core\App\Repository\AppLogRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\App\AppError;

class AppErrorController extends Controller
{
    protected $repository; 

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return AppError::create([
            'error' => is_string($request->error)? $request->error : json_encode($request->error)
        ]); 
    } 
    
 
     
}
