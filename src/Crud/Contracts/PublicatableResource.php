<?php 
namespace Core\Crud\Contracts;

interface PublicatableResource
{
	public function getAvailableStatuses();  
	public function getStatusColumn();  
}