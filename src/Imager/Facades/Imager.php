<?php 
namespace Core\Imager\Facades;

use Illuminate\Support\Facades\Facade;


class ImagerFacade extends Facade
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
        return 'armin.imager';
    }
}
