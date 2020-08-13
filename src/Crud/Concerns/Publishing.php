<?php 
namespace Core\Crud\Concerns;
use Core\User\Concerns\Ownable;
use Core\Crud\Statuses;

trait Publishing
{  
    public function isVisible()
    {
        if($this->isPublished()) {
            return true;
        }

        if($this instanceof Ownable && $this->owner === request()->user()) {
            return true;
        } 

        return is_admin();
    } 

    public function isPublished()
    { 
        $status = $this->getCurrentStatus();
        $publishStatus = $this->getPublishStatus();

        if($status === Statuses::key($publishStatus)) {
            return true;
        }

        if($status === Statuses::key('scheduled')) {
            // check publish time
            return true;
        }
        
        return false;
    }  

    protected function getCurrentStatus()
    {
        $column = $this->getStatusColumn();
        

        return $this->$column;
    }

    public function getStatusColumn()
    {
        return property_exists($this, 'statusColumn') ? $this->statusColumn : 'status';
    }

    public function getPublishStatus()
    {
        return property_exists($this, 'publishStatus') ? $this->publishStatus : 'published';
    } 

    public function scopePublished($query)
    {   
        return $query
                ->where($this->getStatusColumn(), $this->getPublishStatus())
                ->orWhere(function($q)  {
                    $q
                        ->where($this->getStatusColumn(), Statuses::key('scheduled'))
                        ->where('release_date', '<=', now())
                        ->where('finish_date', '>', now());
                });
    }
}