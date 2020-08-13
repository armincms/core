<?php 
namespace Core\Crud\Contracts;

interface Publicatable
{
	public function isVisible(); 
	public function isPublished(); 
}