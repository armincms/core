<?php 
namespace Core\User\Concerns; 

use Core\User\Models\Admin;
use Core\User\Models\User;

trait HasOwnable
{
    
	public function ownerField($name='owner', $type=null, $label=[], $attrs=[], $wrap_attrs=[])
	{
		$owners = $this->getOwners($type);

		$this
			->element('hidden', "{$name}_type", null,  [
				'class' => input_name_id($name). "-ownable ownable-type"
			])
			->element('hidden', "{$name}_id", null,  [
				'class' => input_name_id($name). "-ownable ownable-id"
			])
			->field(
				'select', 
				"ownable_{$name}", 
				false, 
				empty($label) && ! is_null($label)? 'user-management::title.owner' : $label, 
				$owners->map->mapWithKeys(function($user, $key) { 
					$title = "{$user->firstname} {$user->lastname}";

					if(empty(preg_replace('/\s+/', '', $title))) {
						$title = $user->username;
					}

					return [$user->getMorphClass() . "{$user->id}" => $title];
				}),
				[$this->getSelectedOwner($name)], 
				[
					'role' 	=> 'ownable',
					'data-target'=> input_name_id($name). '-ownable'
				] + (array) $attrs,
				$wrap_attrs 
			)
			->pushScript(
				'ownable-script', '$("[role=ownable]").change(function(event) {
		/* Act on the event */
		var $value  = $(this).val();
		var $target = $(this).data("target");
		var $id     = $value.match(/[0-9]+$/).join();
		var $type   = $value.match(/^[^0-9]+/).join();
		$(".ownable-type." + $target).val($type);
		$(".ownable-id." + $target).val($id);  
	}).change()'
			);

		return $this;
	}

	protected function getOwners($type = null)
	{
		switch ($type) {
			case 'user':
				return collect([armin_trans('armin::title.users') => $this->getUsers()]);
				break;
			
			case 'admin':
				return collect([armin_trans('armin::title.admins') => $this->getAdmins()]);
				break;
			
			default:
				return $this->getOwners('admin')->merge($this->getOwners('user'));
				break;
		}  
	}

	protected function getUsers()
	{
		return User::all();
	}

	protected function getAdmins()
	{
		return Admin::all();
	}

	protected function getSelectedOwner($name)
	{ 
		if(! isset($this->model)) {
			return null;
		}

		$id		= "{$name}_id";
		$type 	= "{$name}_type";

		if(is_null($this->model->$id) || is_null($this->model->$type)) {
			$user = request()->user();

			return "{$user->getMorphClass()}{$user->id}";
		}

		return "{$this->model->$type}{$this->model->$id}";
	}
}