<?php 
namespace Core\Document; 
use Html;

class HtmlMetaBuilder 
{   
	public function meta(String $name, String $content = null, array $options = null)
	{
		if(is_null($content)) {
			$content = '';
		}

		$attributes = array_merge((array) $options, compact('name', 'content')); 

		return '<meta '. Html::attributes($attributes).'>';  
	}   
}
