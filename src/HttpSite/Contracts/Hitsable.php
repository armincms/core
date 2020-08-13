<?php 
namespace Core\HttpSite\Contracts;

interface Hitsable
{
	public function increaseVisiting();
	public function decreaseVisiting();
}
