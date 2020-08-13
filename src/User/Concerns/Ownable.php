<?php  
namespace Core\User\Concerns;

trait Ownable 
{    
    public function owner()
    {
        return $this->morphTo();
    } 
}