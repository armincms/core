<?php  
namespace Core\User\Concerns;

trait HasOwner 
{    
	public static function bootHasOwner()
	{
		self::saved(function($model) { 
			$model->load('owner');

			if(is_null($model->owner) && request()->user()) {  
				$model->owner()->associate(request()->user());
				$model->save();
			}
		});
	}

    public function owner()
    {
        return $this->morphTo();
    } 
}