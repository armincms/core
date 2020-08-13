<?php 
namespace Core\Document\Facades;

use Illuminate\Support\Facades\Facade;

class HtmlMeta extends Facade
{ 
    static public function getFacadeAccessor()
    {
        return 'document.html.meta';
    }
}
