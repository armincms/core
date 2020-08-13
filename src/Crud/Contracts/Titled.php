<?php 
namespace Core\Crud\Contracts;

interface Titled
{
	public function setTitle(string $title);
	public function getTitle();
}