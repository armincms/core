<?php  
namespace Core\Crud\Facades;

use Illuminate\Support\Facades\Facade;

class ArminResource extends Facade
{
    
    public static function getFacadeAccessor()
    {
    	return 'armin.resource';
    }
}