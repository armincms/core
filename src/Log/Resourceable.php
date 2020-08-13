<?php
namespace Core\Log;
 
use Illuminate\Database\Eloquent\Model;

/**
 * This trait shoud insert into user model.
 */
trait Resourceable 
{ 
	use Loggable;

	/**
	 * Append resource to created resources.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function createdResource($limit = 15)
	{
		return $this->resources('create', $limit);
		
	}

	/**
	 * User|Admin created resources.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function resources($action = [], $limit = 15)
	{
		return $this->log()->where(function ($q) use ($action) {
			empty($action)?: $q->whereIn('action', (array) $action);
		})->with('loggable')->get()->pluck('loggable');
	}

	/**
	 * Append resource to created resources.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function updatedResource($limit = 15)
	{  
		return $this->resources('edit', $limit);
	} 

}