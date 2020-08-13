<?php 
namespace Core\Crud\Concerns;
 
use Illuminate\Support\HtmlString;

trait HasResponsiveButton
{    
	public function responsiveButtons($target, $grid = [], $size = null)
	{
		$this->pushScript('responsive-buttons', $this->getResponsiveButtonsScript());

		$responsive = empty($grid) ? collect(config('armin.template.responsive')) : collect($grid);

		$this->field(
			'checkable', "{$target}-responsive-buttons", false, null,
			$responsive->map(function($size, $key) use ($target) {
				return [
					'label' => new HtmlString($this->responsiveIcon($key)), 
					'attributes' => [
						'role'  => "responsive-button",
						'data-target' => $target
					]
				];
			})->toArray(), 'mp', true, [], ['class' => 'block-label button-height '. $size]
		);

		return $this; 
	}

	public function responsiveIcon($size)
	{
		return '<span class="icon"><img src="/admin/rtl/img/' 
				. $size
				. '.png" height="100%"></span>';
	}

	public function getResponsiveButtonsScript()
	{
		return view('admin-crud::components.responsive-buttons-script')->render();
	}
}