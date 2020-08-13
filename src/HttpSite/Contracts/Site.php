<?php 
namespace Core\HttpSite\Contracts;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

interface Site
{
	public function setName(String $name);  
	public function getName();  
	public function setTemplate(Object $name);  
	public function getTemplate();  
}
