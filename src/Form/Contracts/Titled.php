<?php 
namespace Core\Form\Contracts;

interface Titled
{
	public function setTitle(string $title);
	public function getTitle();
}