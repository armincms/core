<?php 
namespace Core\HttpSite\Repository; 


use Core\Template\Contracts\Repository; 
use Core\Template\Contracts\Template as TemplateInterface;

class TemplateRepository implements Repository
{   
    public function retrieveByName(String $name) : TemplateInterface 
    {
        return new Template;
    }
}