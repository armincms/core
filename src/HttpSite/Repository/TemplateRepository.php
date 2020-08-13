<?php 
namespace Core\HttpSite\Repository; 


use Armincms\Template\Contracts\Repository; 
use Armincms\Template\Contracts\Template as TemplateInterface;

class TemplateRepository implements Repository
{   
    public function retrieveByName(String $name) : TemplateInterface 
    {
        return new Template;
    }
}