<?php

namespace Core\App\Http\Controllers\Api;

use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Core\App\Repository\AppLogRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Armin\AppLog;

class AppLogController extends Controller
{
    protected $repository;

    protected $defaultColumns = [
        'imei', 'status', 'os', 'os_version', 'app_version', 'mobile', 
    ];

    function __construct(AppLogRepository $repository)
    {
        $this->repository = $repository; 
    }

    public function index(Request $request)
    {
        return $request;
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(! $request->has('imei')) {
            return $request->all();
        }

        $log = $this->repository->updateOrCreate(
            ['imei' => $request->get('imei')],
            $this->fetchData($request)
        );

        $this->setMeta($log, $request);
        $log->save(); 

        return $log->load('metas')->toArray();
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
        $log = $this->repository->firstOrCreate(['imei' => $appLog]);
        if($log) {
            $log = $this->repository->update(
                $this->fetchData($request, $log),
                $log->id
            );

            $this->setMeta($log, $request);

            $log->save();

            return $log->load('metas')->toArray();
        } 

        throw (new ModelNotFoundException)->setModel(
            $this->repository->model(), $appLog
        );
    }


    function fetchData(Request $request, $log = null)
    {
        return [
            'imei'  => $request->get('imei', array_get($log, 'imei')),
            'status'=> $request->get('status', 1),
            'os'    => $request->get('os', array_get($log, 'os')),
            'os_version'    => $request->get('os_version', array_get($log, 'os_version')),
            'app_version'   => $request->get('app_version', array_get($log, 'app_version')),
            'mobile' => $request->get('mobile', array_get($log, 'mobile')), 
        ];
    }

    function setMeta($log, Request $request)
    {
        $metas = $request->except($this->defaultColumns + [
            '_token' => '_token', '_method' => '_method'
        ]);

        foreach ($metas as $key => $value) {
            $log->setMeta($key, $value);  
        }
        
    }
     
}
