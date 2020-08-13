<?php  
namespace Core\User\Contracts;

interface Ownable
{
	/**
	 * Find Resource Owner.
	 * 
	 * @return \Illuminate\Foundation\Auth\User
	 */
	public function owner(); 
}