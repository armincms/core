<?php 
namespace Core\Crud\Policies;


class ResourcePolicy
{
    public function __call($method, $arguments = [])
    {
    	return \Auth::guard('admin')->check();
    }
}
