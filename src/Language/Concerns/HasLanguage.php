<?php 
namespace Core\Language\Concerns;

trait HasLanguage
{
	public function languageSelection($name='language',$active=false,$attrs=[],$wrap_attrs=[])
	{
		$this->field('select', $name, false, 'language::title.language', language()->filter(function($language, $active) { return !$active || $language->active(); }
		)->pluck('label', 'locale'), [], $attrs, [], [], $wrap_attrs);

		return $this;
	}
}