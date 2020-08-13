<?php 
namespace Core\Armin\Facades;

use Illuminate\Support\Facades\Facade;


class HelperFacade extends Facade
{  
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'helper';
    }
}
