<?php 
namespace Core\Imager;  

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

class SchemaRepository extends Repository
{   


    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return Collection::make($this->items);
    }

	public function whereInGroup(array $groups)
	{ 
		return $this->all()->filter(function($schema) use ($groups) { 
 			$group = array_get($schema, 'group', '*');

 			return in_array($group, $groups);
 		});
	}

 	public function whereGroup(string $group)
 	{ 
 		return $this->whereInGroup([$group]); 	
 	}

 	public function global()
 	{
 		return $this->whereGroup('*');
 	}

 	public function withGlobals($group)
 	{
 		$groups = is_array($group) ? $group : func_get_args();
 		$groups[] = '*';

 		return $this->whereInGroup($groups);
 	}

    public function find(string $name)
    {
        return $this->all()->first(function($schema, $key) use ($name) {
            return array_get($schema, 'name', $key) == $name;
        });
    }
}	
