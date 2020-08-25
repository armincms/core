<?php 
namespace Core\Template; 

use Core\Template\Template;
use Core\Template\Repository; 

class Factory
{
    /**
     * Moduel repository.
     * 
     * @var \Core\Moduel\Repositories\Repository
     */
    protected $templates;

    public function __construct(Repository $templates)
    {
        $this->templates = $templates; 
    }

    public function make(string $name)
    {
        return $this->templates->template($name); 
    } 

    public function resolveRouteBinding($name)
    { 
        return $this->make($name);
    } 
}
