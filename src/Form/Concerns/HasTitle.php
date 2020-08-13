<?php 
namespace Core\Form\Concerns;


trait HasTitle
{ 
    public function setTitle(string $title)
    {
    	$this->title = $title;

    	return $this; 
    }

    public function getTitle()
    { 
    	return $this->title; 
    }
}