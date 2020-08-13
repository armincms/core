<?php 
namespace Core\HttpSite\Concerns; 

use Core\HttpSite\Component; 
use Site; 

trait IntractsWithSite
{  
    public function site()
    { 
        return Site::findByComponent($this->component());
    } 

    abstract public function component() : Component;
}