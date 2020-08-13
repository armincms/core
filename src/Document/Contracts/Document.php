<?php 
namespace Core\Document\Contracts;

interface Document
{   
	public function charset(String $charset);

	public function locale(String $locale);

	public function setContent(String $content = null);
}
