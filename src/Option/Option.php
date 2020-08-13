<?php 
namespace Core\Option;

use Illuminate\Contracts\Support\Jsonable; 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;  
use JsonSerializable; 
use Carbon\Carbon;

class Option extends Model 
{
    protected $guarded = [];  

    public $timestamps = false; 

    public function setValueAttribute($value)
    {
        $type = 'string';

        if(is_integer($value) || is_numeric($value) && (int) $value == $value) { 
            $type = 'integer';
            $value = (int) $value;
        } else if(is_bool($value) || $value === 'true' || $value === 'false') {
            $type = 'boolean';
            $value = (boolean) $value ? 'true' : 'false';
        } else if(is_float($value)) { 
            $type = 'float';
            $value = floatval($value);
        } else if(is_double($value)) { 
            $type = 'double';
            $value = doubleval($value);
        } else if(is_string($value) && strtotime($value)) { 
            $type = 'datetime';
        } else if (is_array($value)) {
            $type = 'array';
            $value = json_encode($value); 
        } elseif ($value instanceof Arrayable) {
            $type = 'object';
            $value = json_encode($value->toArray());
        } elseif ($value instanceof Jsonable) {
            $type = 'object';
            $value = $value->toJson();
        } elseif ($value instanceof JsonSerializable) {
            $type = 'object';
            $value = $value->jsonSerialize();
        } elseif (empty($value)) {
            $type = 'null';
            $value = null;
        } 

        $this->attributes['value']  = $value;
        $this->attributes['type']   = $type;
    }

    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'array':
            case 'collection':
            case 'object':
                return collect(json_decode($value, true)); 
                break;
            case 'boolean': 
                return (boolean) $value; 
                break;
            case 'integer': 
                return (int) $value; 
                break;
            case 'float': 
                return floatval($value); 
                break;
            case 'double': 
                return doubleval($value); 
                break;
            case 'null': 
                return null; 
                break;
            case 'datetime': 
                return Carbon::parse($value);
                break;
            
            default:
                return (string) $value;
                break;
        } 
    }
}
