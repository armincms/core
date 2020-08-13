<?php  
 
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

 
 
if(! function_exists('armin_dropdown')) {
    /**
     * Make dropdown from array of items .
     * 
     * @param  array   $parents  
     * @param  Clusre  $get_child   [need callabel function for find child(s) of item]
     * @param  Clusre  $drawer  [need callabel function for draw an item and its child(s)]
     * @param  integer $depth   [depth of parent]
     * @return string          
     */
    function armin_dropdown(array $parents, $get_child, $drawer, $depth=0)
    {     
        $dropdowned = ''; 

        foreach ($parents as $parent) {   

            if (! is_callable($get_child)) 
                return "Missing Callbak Function For Fetch Childs Of Menu [id: {$parent->id}] In Depth {$depth}.";

            if (! is_callable($drawer)) 
                return "Missing Callbak Function For Draw Menu [id: {$parent->id}] In Depth {$depth}."; 

            // find childs of  mneu
            $childs = call_user_func_array($get_child, [$parent]);

            // print created childs
            ob_start(); 
                echo call_user_func_array('armin_dropdown', [$childs, $get_child, $drawer, $depth+1]);
            $printed = ob_get_clean();
            // recall
            $dropdowned .= call_user_func_array($drawer, [$parent, $printed, $depth, $childs]);  
        }   

        return $dropdowned;
    }
}

if (! function_exists('armin_slug')) { 
    /**
     * Make url slug for string.
     *
     * @param  string  $string 
     * @param  array   $options 
     * 
     * @return string
     */
    function armin_slug(string $string = null, $options = [])
    {   
        if(empty($string)) {
            return $string;
        }

        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $string = mb_convert_encoding((string)$string, 'UTF-8', mb_list_encodings());

        // Options
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => false,
            'replacements' => array(),
            'transliterate' => false,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
            // Persian
            'ة' => 'ه', 'ۀ' => 'ه', 'ؤ' => 'و', 'ي' => 'ی', 'ك' => 'ک', 'ء' => '', 'أ' => 'ا', 'إ' => 'ا',
            "٤" => "۴", "٥" => "۵", "٦" => "۶", 'ـ' => '_',
        );

        // Make custom replacements
        $string = preg_replace(array_keys($options['replacements']), $options['replacements'], $string);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $string = str_replace(array_keys($char_map), $char_map, $string);
        }

        // Replace non-alphanumeric characters with our delimiter
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $string);

        // Remove duplicate delimiters
        $string = preg_replace(
            '/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $string
        );

        // Truncate slug to max. characters
        $string = mb_substr($string, 0, ($options['limit'] 
                            ? $options['limit'] 
                            : mb_strlen($string, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $string = trim($string, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($string, 'UTF-8') : $string; 
    }
}

if (! function_exists('use_trait')) { 
    /**
     * Check if class using trait.
     *
     * @param  mixed $model 
     * @param  \Trait  $trait 
     * 
     * @return boolean
     */
    function use_trait($model, $trait)
    {   
        return in_array($trait, class_uses($model));
    }
}

if (! function_exists('use_soft_deletes')) { 
    /**
     * Check if class using Illuminate\Database\Eloquent\SoftDeletes.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model 
     * 
     * @return boolean
     */
    function use_soft_deletes($model)
    {   
        return use_trait($model, 'Illuminate\Database\Eloquent\SoftDeletes');
    }
}

if (! function_exists('input_name_prefix')) { 
    /**
     * Append a prefix to input name.
     *
     * @param  string  $prefix 
     * @param  string  $name 
     * 
     * @return string
     */
    function input_name_prefix($prefix=null, $name=null)
    {   
        if(empty($name)) {
            return $prefix;
        }

        if(empty($prefix)) {
            return $name;
        }

        preg_match_all('/[^\[\]]+/', $name, $matches);
            
        $wrraped = implode($matches[0], ']['); 
             
        return "{$prefix}[{$wrraped}]"; 
    }
}

if (! function_exists('input_name_id')) { 
    /**
     * Convert input name to approperiat string id.
     * 
     * @param  string  $name 
     * 
     * @return string
     */
    function input_name_id($name)
    {   
        return trim(
            preg_replace('/[^a-zA-Z-0-9]+/', '-', $name), '-'
        );
    }
}

if (! function_exists('crud_event')) { 
    /**
     * Make new CRUD event.
     *
     * @param  string  $event
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return object
     */
    function crud_event($event, Model $resource, Authenticatable $user)
    {   
    	if(! class_exists($event)) {
    		$event = config('armin.crud.event.{$event}');
    	}  

        return event(
        	new $event($resource, $user)
    	);
    }
}

if (! function_exists('creating_resource')) { 
    /**
     * Make new creating resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\CreatingResource
     */
    function creating_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\CreatingResource::class, $resource, $user);
    }
}

if (! function_exists('created_resource')) { 
    /**
     * Make new created resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\CreatedResource
     */
    function created_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\CreatedResource::class, $resource, $user);
    }
}

if (! function_exists('editing_resource')) { 
    /**
     * Make new editing resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\EditingResource
     */
    function editing_resource(Model $resource, Authenticatable $user)
    {    
        return crud_event(\Core\Crud\Events\EditingResource::class, $resource, $user);
    }
}

if (! function_exists('updating_resource')) { 
    /**
     * Make new updating resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\UpdatingResource
     */
    function updating_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\UpdatingResource::class, $resource, $user);
    }
}

if (! function_exists('updated_resource')) { 
    /**
     * Make new updated resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\UpdatedResource
     */
    function updated_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\UpdatedResource::class, $resource, $user);
    }
} 

if (! function_exists('destroying_resource')) { 
    /**
     * Make new destroying resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\DestroyingResource
     */
    function destroying_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\DestroyingResource::class, $resource, $user);
    }
}

if (! function_exists('destroyed_resource')) { 
    /**
     * Make new destroyed resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\DestroyedResource
     */
    function destroyed_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\DestroyedResource::class, $resource, $user);
    }
}


if (! function_exists('deleting_resource')) { 
    /**
     * Make new deleting resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\DeletingResource
     */
    function deleting_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\DeletingResource::class, $resource, $user);
    }
}

if (! function_exists('deleted_resource')) { 
    /**
     * Make new deleted resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\DeletedResource
     */
    function deleted_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\DeletedResource::class, $resource, $user);
    }
}

if (! function_exists('restoring_resource')) { 
    /**
     * Make new restoring resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\RestoringResource
     */
    function restoring_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\RestoringResource::class, $resource, $user);
    }
}

if (! function_exists('restored_resource')) { 
    /**
     * Make new restored resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\RestoredResource
     */
    function restored_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\RestoredResource::class, $resource, $user);
    }
}

if (! function_exists('publishing_resource')) { 
    /**
     * Make new publishing resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\PublishingResource
     */
    function publishing_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\PublishingResource::class, $resource, $user);
    }
}

if (! function_exists('published_resource')) { 
    /**
     * Make new published resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\PublishedResource
     */
    function published_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\PublishedResource::class, $resource, $user);
    }
}

if (! function_exists('unpublishing_resource')) { 
    /**
     * Make new unpublishing resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\UnpublishingResource
     */
    function unpublishing_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\UnpublishingResource::class, $resource, $user);
    }
}

if (! function_exists('unpublished_resource')) { 
    /**
     * Make new unpublished resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\UnpublishedResource
     */
    function unpublished_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\UnpublishedResource::class, $resource, $user);
    }
}

if (! function_exists('reading_resource')) { 
    /**
     * Make new reading resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\ReadingResource
     */
    function reading_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\ReadingResource::class, $resource, $user);
    }
}

if (! function_exists('read_resource')) { 
    /**
     * Make new read resource event.
     * 
     * @param  Illuminate\Database\Eloquent\Model  $resource
     * @param  Illuminate\Foundation\Auth\User  $user
     * 
     * @return \Core\Crud\Events\ReadResource
     */
    function read_resource(Model $resource, Authenticatable $user)
    {   
        return crud_event(\Core\Crud\Events\ReadResource::class, $resource, $user);
    }
} 

 
if (! function_exists('presenting_resource')) { 
    /**
     * Make new presenting resource event.
     *
     * @param  Illuminate\Database\Eloquent\Model  $resource 
     * @return \Core\Crud\Events\PresentingResource
     */
    function presenting_resource($resource)
    {    
        return event(new \Core\Crud\Events\PresentingResource($resource)); 
    }
}  
