<?php 
namespace Core\Contracts; 
 

interface Multilingual 
{ 
   	/**
   	 * Get Translations.
   	 * 
   	 * @return \Illuminate\Database\Eloquent\Relations
   	 */
	public function translates(); 

	/**
	 * Get Specific Translation.
	 * 
	 * @param  String $locale
	 * @return \Illuminate\DAtabase\Eloquent\Model | null
	 */
	public function translate(String $locale); 

	/**
	 * TRanslate Specific Attribute.
	 * 
	 * @param  String      $key     
	 * @param  String|null $locale  
	 * @param  mixed      $default 
	 * @return mixed               
	 */
	public function trans(String $attributeKey, String $locale=null, $default=null);
} 