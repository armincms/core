<?php 
namespace Core\HttpSite\Contracts;


interface Registrar
{
	public function register(Presenter $presenter, array $options = null); 
}
