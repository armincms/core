<?php 
namespace Core\Contracts;

interface Bootable
{
	/**
	 * For load manifetst.
	 * 
	 * @return void
	 */
	public function boot();
}
