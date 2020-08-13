<?php
namespace Core\Language;
 
use Illuminate\Support\Fluent;  

class Locale extends Fluent
{   
	public function active()
	{
		return (boolean) $this->active;
	}
}
