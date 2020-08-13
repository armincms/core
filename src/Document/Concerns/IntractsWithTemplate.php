<?php 
namespace Core\Document\Concerns;

use Core\Contracts\Extensible;  

trait IntractsWithTemplate
{  
	/**
	 * List of modules.
	 * 
	 * @var array
	 */
	protected $template = null;   

	public function setTemplate(Extensible $template)
	{
		$this->template = $template;

		return $this;
	} 

	public function template()
	{
		return $this->template;
	} 

	public function loadTemplatePlugins()
	{
		$order = - abs($this->plugins()->count());

		$this->pushPlugins((array) $this->template->plugins(), $order++); 

		return $this; 
	}

	public function loadTemplateStyleSheets()
	{ 
		$order = abs($this->sheets()->count());
		
		$this->pushSheet($this->template->css(), $order++); 

		return $this; 
	}

	public function sectionIsActive($section)
	{ 
		return $this->sectionGrade($section, false)->count() != $this->grades()->count();
	}

	public function sectionGrade($section, $activation = true)
	{
		return $this->grades()->filter(
			function($responsive) use ($section, $activation) { 
				// dump("{$activation}:{$section}:{$responsive}", $activation);
				return (boolean) $activation === $this->isActiveGrade($section, $responsive);
			}
		);
	}

	public function activeSectionGrade($section)
	{
		return $this->sectionGrade($section, true);
	}

	public function inactiveSectionGrade($section)
	{ 
		return $this->sectionGrade($section, false);
	}

	public function inactiveGrades($section)
	{
		return $this->inactiveSectionGrade($section)->map(function($responsive) {
			return "{$responsive}-hidden";
		});
	}  

	public function mainGrades()
	{ 
		$leftsideGrades = $this->leftsideGrades();
		$rightsideGrades = $this->rightsideGrades();

		return $this->grades()->map(function($responsive) use ($leftsideGrades, $rightsideGrades) {
			$column = $this->baseGrade();

			$column -= (int) $leftsideGrades->get($responsive);
			$column -= (int) $rightsideGrades->get($responsive);
			 

			return $this->gradeClass($column, $responsive); 
		});
	}

	public function leftsideGrades()
	{
		return $this->sidebarGrades('left');
	}

	public function rightsideGrades()
	{
		return $this->sidebarGrades('right');
	}

	public function sidebarGrades($side)
	{
		return $this->sidebarGradesSize($side)->map(function($column, $responsive) {
			if($column > 0) {
				return $this->gradeClass($column, $responsive); 
			}

			return "{$responsive}-hidden"; 
		});
	}

	public function sidebarGradesSize($side)
	{ 
		return $this->grades()->mapWithKeys(function($responsive) use ($side) {
			$size = 0;

			if($this->isActiveGrade("{$side}side", $responsive)) {
				$baseGrade = $this->baseGrade();
				$default 	= (int) ceil($baseGrade * 0.25);

				$size = min(
					$this->setting("rightside.{$responsive}.column", $default), $baseGrade
				); 
			}

			return [$responsive => $size]; 
		});
	}

	public function gradeClass($column, $responsive)
	{
		$modulo = $this->maxModulo($this->baseGrade(), $column);
		$size 	= $column/$modulo ."-" . $this->baseGrade()/$modulo;

        return "{$responsive}-{$size}";
	}

	public function maxModulo($num1, $num2)
	{
		if(! is_numeric($num1) || ! is_numeric($num2)) return 1;

        $num2 = (int) $num2;
        $num1 = (int) $num1;
        $i = 12;

        while($i > 0 && ($num1 % $i != 0 || $num2 % $i != 0)) {
            $i--; 
        }

        return $i;
	}

	public function isActiveGrade($section, $responsive, $default = true)
	{
		return (boolean) $this->setting("{$section}.{$responsive}.active", $default);
	}

	public function grades()
	{
		return collect(config('armin.template.responsive'))->keys();
	} 

	public function baseGrade()
	{
		return (int) $this->setting('middle.base_column', 12)?: 12;  
	} 

	public function containerWidth($section, $default = 'container')
	{
		return $this->setting("{$section}.width", $default);
	}

	public function setting($key = null, $default = null)
	{ 
		return $this->template->setting($key, $default);
	} 
}
