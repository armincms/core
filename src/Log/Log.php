<?php 
namespace Core\Log;

use Illuminate\Database\Eloquent\Model;
use Request;

class Log extends Model
{  
    protected $guarded = []; 
    protected $attributes = [
    ];
 
    public static function boot()
    {
       parent::boot(); 

       self::saving(function($log) {
       		$log->ip = Request::ip(); 
       }); 
    } 

	/**
	 * Create new log for model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function author()
	{ 
		return $this->morphTo('author');
	} 

	/**
	 * Create new log for model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function loggable()
	{ 
		return $this->morphTo('loggable');
	} 
}
