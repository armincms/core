<?php
namespace Core\Log;
 
use Illuminate\Database\Eloquent\Model;
use Core\User\Models\Admin;
use Auth; 

/**
 * This trait shoud insert into resource model.
 */
trait Loggable
{   
	use Morphable;  

	protected $morphableClass = '\Core\Log\Log';

	private $limitedAttribute = [
		'author', 'editor', 'publisher'
	];

	/**
	 * Create new log for model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function log($action = null, Model $user = null)
	{ 
		$log = $this->morphMany(
			$this->getMorphableClass(),
			$this->getMorphableName(), 
			$this->getMorphableType(), 
			$this->getMorphableId()
		)->with('author'); 

		return isset($action) ? $this->modifier($action, $user) : $log;
	}    


	/**
	 * Create new log for model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function modifier($action = 'edit', Model $user = null)
	{   
		$log = $this->log()->where([
			'action' => $action,
			'author_id' => @$user->id,
			'author_type' => get_class($user)
		])->first(); 

		if (! isset($log)) {
			$log = $this->log()->firstOrCreate(['action' => $action, 'author_id' => @$user->id]);

			$log->increment('count');

			$log->author()->associate($user)->save();
		} 

		$log->increment('count'); 

		return $log;
	} 
 
	/**
	 * Get loggable throuth this model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function logger($action = null, $limit = 15)
	{ 
		return $this->log()->where(function ($q) use ($action) {
			empty($action) ?: $q->whereIn('action', (array) $action);
		})->latest('updated_at')->latest('updated_at')->paginate($limit);
	}

	/**
	 * User that has created this.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function author()
	{         
		return $this->editor('create');  
	}    

	/**
	 * Append or Retrieve User that has edited resource.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function editor($action = 'update')
	{    
		$log = $this->logger($action, 1)->first(); 

		if (! isset($log)) {
			$log = $this->log($action, $this->getDefaultAdmin());
		} 

		return $this->hasManyThrough($log->author, $log, 'loggable_id', 'id', 'id', 'author_id')
			->where($log->getTable() .'.author_id', $log->author->id)
			->where($log->getTable() .'.action', $action) 
			->latest($log->getTable().'.updated_at')
			->limit(1); 
	}

	/**
	 * User that has created this.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	protected function getDefaultAdmin()
	{ 
 		if($admin = Admin::find(2)) {
 			return $admin;
 		}

		return Admin::first();
	}

	/**
	 * Append or Retrieve User that has edited resource.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function publisher()
	{    
		return $this->editor('publish');
	} 

	/**
	 * Append or Retrieve User that has edited resource.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function editors($limit = 15)
	{    
		return $this->logger(['edit', 'publish'], $limit)->pluck('author');
	}

	/**
	 * Append or Retrieve User that has edited resource.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function publishers($limit = 15)
	{    
		return $this->logger(['publish'], $limit)->pluck('author');
	}

	/**
	 * Append or Retrieve User that has edited resource.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function history($limit = 15)
	{    
		return $this->log()->latest('updated_at')->paginate($limit);
	}


    /**
     * Get a relationship.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getRelationValue($key)
    { 
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->hasLimited($key) ? $this->relations[$key]->first() : $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key)) { 
            return $this->hasLimited($key) 
            			? $this->getRelationshipFromMethod($key)->first() 
            			: $this->getRelationshipFromMethod($key); 
        }
    }  

    protected function hasLimited($key)
    {
    	return in_array($key, $this->limitedAttribute);
    }
}