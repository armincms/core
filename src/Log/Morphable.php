<?php
namespace Core\Log;
 
use Illuminate\Database\Eloquent\Model;

/**
 * This trait shoud insert into resource model.
 */
trait Morphable 
{   

	/**
	 * Morphableable class.
	 * 
	 * @return string
	 */
	protected function getMorphableClass()
	{
		return isset($this->morphableClass) ? $this->morphableClass : null;
	} 

	/**
	 * Morphableable name.
	 * 
	 * @return string
	 */
	protected function getMorphableName()
	{
		return isset($this->morphableName) ? $this->morphableName : 'loggable';
	}  

	/**
	 * Morphableable type.
	 * 
	 * @return string
	 */
	protected function getMorphableType()
	{
		return isset($this->morphableType) ? $this->morphableType : null;
	}

	/**
	 * Morphableable id.
	 * 
	 * @return string
	 */
	protected function getMorphableId()
	{
		return isset($this->morphableId) ? $this->morphableId : null;
	} 
}