<?php

namespace Core\Language;
 
use Illuminate\Database\Eloquent\Model;

trait Translatable 
{    

    public function setAssocKeyAttribute($assocKey)
    {     
        $this->attributes['assoc_key'] = $assocKey? $assocKey: md5(uniqid(time(), true)); 
    } 
    
    /**
     * Create new log for model.
     * 
     * @return \Illuminate\Eloquent\Model
     */
    public function translate()
    {      
        return $this->hasMany($this, 'assoc_key', 'assoc_key') 
                        ->where('id', '!=', $this->id)->limit(language()->count());  
    } 

    /**
     * Create new log for model.
     * 
     * @return \Illuminate\Eloquent\Model
     */
    public function translates()
    {    
        // $query = $this->join(
        //     'translatables' , 
        //     $this->qualifyColumn('id'),
        //     'translatables.content_id' 
        // )->join("{$this->getTable()} as x", "x.id", '<>', $this->qualifyColumn('id'));

        // $morph = $this->morphedByMany($this, 'translatable', null, 'content_id')
        //                 ->limit(language()->count() - 1)->join("{$this->getTable()} as x", "x.id", '<>', $this->qualifyColumn('id'));

        // dd($query->toSql(), $morph->toSql());

        return $this->morphedByMany($this, 'translatable', null, 'assoc_key', 'assoc_key')
                        ->limit(language()->count() - 1);  
    } 

	/**
	 * Create new log for model.
	 * 
	 * @return \Illuminate\Eloquent\Model
	 */
	public function translatedBy()
	{     
		return $this->morphToMany($this, 'translatable', null, null, 'content_id')
                        ->limit(language()->count() - 1);  
	} 
    
    function __call($method, $params)
    {
    	if(starts_with($method, 'translate')) {

    		$locale = strtolower(str_replace('translate', '', $method));

    		$key = is_numeric($locale) ? 'language_id' : 'language';

    		return $this->hasOne($this, 'assoc_key', 'assoc_key') 
						->where($key, $locale) 
						->withDefault();
    	} 

    	return call_user_func_array([parent::class, $method], $params);
    }
}
