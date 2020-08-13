<?php 
namespace Core\Language; 

use Illuminate\Database\Eloquent\Model; 

class Translate extends Model
{    
	public $timestamps = false;
	protected $guarded = []; 


    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
    	$instance = parent::newInstance($attributes, $exists);

    	return $instance->setTable($this->table); 
    }
}
