<?php 
namespace Core\Document\Facades;

use Illuminate\Support\Facades\Facade;

class HtmlPlugin extends Facade
{ 
    static public function getFacadeAccessor()
    {
        return 'document.html.plugin';
    }
}
