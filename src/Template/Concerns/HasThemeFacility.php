<?php 
namespace Armincms\Template\Concerns;

use Illuminate\Support\HtmlString;

trait HasThemeFacility
{
	public function themeFacilityFields(string $name = 'facility', $facilities = [])
	{  
		$this  
			->backgroundAndFacilitiesFields($name, (array) $facilities) 
            ->field('select', "{$name}[type]", false, 'template::title.background_mode', [
                null 	=> armin_trans('armin::title.select'), 
                'color' => armin_trans('template::title.color'),
                'gradient' => armin_trans('template::title.gradient'),
            ], null, ['role' => 'gradient'], [], [], [
            	'data-image', 'data-pattern', 'data-slide', 'role' => 'background'
            ]) 
            ->element('hidden', "{$name}[color]")
            ->field('inputSelect', "{$name}[percent]", false, 'template::title.color', null, null, [
            		'values' => theme_colors()->pluck('title', 'name')->all(), 
	            	'name' => $this->appendPrefix("{$name}[color]")
	            ], 
	            ['placeholder' => armin_trans('template::title.percent'), 'min' => 0], 
	            ['data-color', 'data-gradient', 'role' => 'background'], null, 'number') 
            ->field('inputSelect', "{$name}[gradient_percent]", false, 'template::title.gradient', null, null, [
	            	'values' => theme_colors()->pluck('title', 'name')->all(), 
	            	'name' => $this->appendPrefix("{$name}[gradient]")
	            ], 
	            ['placeholder' => armin_trans('template::title.percent'), 'min' => 0], 
	            ['data-gradient', 'role' => 'background'], null, 'number')  
            ->field('text', "{$name}[degree]", false, 'template::title.degree', null, null, ['min' => 0], ['data-gradient', 'role' => 'background'], null, 'number')
            ->field('checkable', "{$name}[linear]", false, 'template::title.gradient_type', [
            	[
            		'name' => $this->appendPrefix("{$name}[linear]"),
            		'label'=> "template::title.linear",
            		'value'=> 1,
            	],
            	[
            		'name' => $this->appendPrefix("{$name}[linear]"),
            		'label'=> 'template::title.radial',
            		'value'=> 0,
            	],
            	['label' => '|', 'name' => '', 'attributes' => ['disabled']],
            	[
            		'name' => $this->appendPrefix("{$name}[repeating]"),
            		'label'=> 'template::title.repeating',
            		'value'=> 1,
            	],
            	[
            		'name' => $this->appendPrefix("{$name}[repeating]"),
            		'label'=> 'template::title.once',
            		'value'=> 0,
            	] 
            ], null, true, [], ['data-gradient', 'role' => 'background'])
            ->field('checkable', "{$name}[size]", false, 'template::title.image_size',[
            	'cover' 	=> 'template::title.cover', 
            	'initial' 	=> 'template::title.initial', 
            	'contain' 	=> 'template::title.contain', 
            ], null, true, [], ['data-image', 'data-slide', 'role=background'])
            ->field('checkable', "{$name}[animation]", false, 'template::title.animation',[
            	'scroll' 	=> [
            		'label' => 'template::title.scroll', 'attributes' => ['role=animation']
            	], 
            	'fixed' 	=> [
            		'label' => 'template::title.fixed', 'attributes' => ['role=animation']
            	], 
            	'smooth' 	=> [
            		'label' => 'template::title.smooth', 'attributes' => ['role=animation']
            	], 
            ], null, true, ['role=5'], ['data-image', 'data-slide', 'role=background'])
            ->field('select', "{$name}[speed]", false, 'template::title.speed', [
            	1 => 1, 2 => 2, 5 => 5, 10 => 10, 20 => 20, 50 => 50
            ], null, [], [], [], ['role' => 'background', 'data-animation']) 
            ->field('checkable', "{$name}[direction]", false, 'template::title.direction', $this->directionLables(), null, true, [], [
            	'role' => 'background', 'data-animation'
            ])
            ->field('text', "{$name}[time]", false, 'template::title.time', 's', null, [
            	'min' => '1'], ['role=background data-slide'], null, 'number')
            ->field('text', "{$name}[top]", false, 'template::title.top', '%', null, [
            	'min' => '-100', 'max' => 100], ['data-image data-slide role=background'], null, 'number')
            ->field('text', "{$name}[left]", false, 'template::title.left', '%', null, [
            	'min' => '-100', 'max' => 100], ['data-image data-slide role=background'], null, 'number');

        return $this->pushScript('theme-facility-script', view('template::theme-facility-script'));
		
	}

	public function backgroundAndFacilitiesFields(string $name, array $facilities = [])
	{
		$facilities = $this->getFacilityList($facilities);

		$this
			->field('select', "{$name}[background]", false, 'template::title.background', 
				$facilities, null, ['role' => 'theme-facility']
            );
        foreach ($facilities as $facility => $title) {
        	if(empty($facility) || in_array($facility, ['color', 'gradient'])) continue;

        	$this->field(
        		'select', "{$name}[{$facility}]", false, "template::title.{$facility}",
        		theme_facilities(str_plural($facility))->pluck('title', 'name')->all(), 
        		null, [], [], [], ["data-{$facility}", 'role' => 'background']
        	);
        } 

        return $this;
	}


	public function getFacilityList(array $facilities = [])
	{
		return collect([
            null 	=> armin_trans('armin::title.select'),
            'color' => armin_trans('template::title.color'),
            'gradient' 	=> armin_trans('template::title.gradient'),
            'pattern' 	=> armin_trans('template::title.pattern'),
            'svg' 	=> armin_trans('template::title.svg'), 
            'image' => armin_trans('template::title.image'),
            'slide'=> armin_trans('template::title.slide'),
            // 'video' => armin_trans('template::title.video'),
        ])->filter(function($facility, $key) use ($facilities) {
        	return empty($key) || empty($facilities) || in_array($key, $facilities);
        })->toArray();  
	}

	public function directionLables()
	{ 
		return [
            'l' 	=> new HtmlString('<i class="icon-left blue"></i>'), 
            'r' 	=> new HtmlString('<i class="icon-right blue"></i>'), 
            't' 	=> new HtmlString('<i class="icon-up blue"></i>'), 
            'b' 	=> new HtmlString('<i class="icon-down blue"></i>'), 
            'bl' 	=> new HtmlString('<i class="icon-down rotate45 blue"></i>'), 
            'br' 	=> new HtmlString('<i class="icon-up rotate135 blue"></i>'), 
            'tl' 	=> new HtmlString('<i class="icon-down rotate135 blue"></i>'), 
            'tr' 	=> new HtmlString('<i class="icon-up rotate45 blue"></i>'),  
        ];
	}

	public function transformFacility($data)
	{
		return $this->transformThemeFacility(array_get($this->getModel(), 'facility', $data));
	}

	public function transformThemeFacility(array $data = [])
	{ 
		if($background = array_get($data, 'background')) { 
			$method = "fetch".ucfirst($background)."background";

			$data = (array) call_user_func_array([$this, $method], [$data]);

			return $data + compact('background');
		}

		return [];
	}

	public function fetchColorBackground($data)
	{
		return [
			'color' => array_get($data, 'color') ?: 'blue',
			'percent' => (int) array_get($data, 'percent') ?: 100,
		];
	}

	public function fetchGradientBackground($data)
	{ 
		return [
			'color' 	=> array_get($data, 'color') ?: 'blue',
			'percent' 	=> (int) array_get($data, 'percent') ?: 100, 
			'gradient' 	=> array_get($data, 'gradient') ?: 'blue',
			'gradient_percent' 	=> (int) array_get($data, 'gradient_percent') ?: 100, 
			'degree' 	=> (int) array_get($data, 'degree'), 
			'linear' 	=> (int) array_get($data, 'linear'), 
			'repeating'	=> (int) array_get($data, 'repeating'), 
		];
	}

	public function fetchPatternBackground($data)
	{ 
		$pattern = $this->getBackgroundColorization($data);  

		$pattern['type'] 	= array_get($data, 'type');
		$pattern['pattern'] = array_get($data, 'pattern');

		return $pattern; 
	}

	public function getBackgroundColorization($data)
	{ 
		if (array_get($data, 'type') === 'gradient') { 
				return $this->fetchGradientBackground($data);
		} else if (array_get($data, 'type') === 'color') { 
				return $this->fetchColorBackground($data);
		} else {  
			return [];
		}
	}

	public function fetchSvgBackground($data)
	{ 
		$svg = array_get($data, 'svg');

		return compact('svg');
	}

	public function fetchImageBackground($data)
	{ 
		$image = $this->getBackgroundColorization($data);  

		$image['type'] 	= array_get($data, 'type');
		$image['image'] = array_get($data, 'image');
		$image['size'] 	= $this->getBackgroundImageSize(array_get($data, 'size'));
		$image['top'] 	= (int) array_get($data, 'top');
		$image['left'] 	= (int) array_get($data, 'left');
		$image['animation'] = $this->getBackgroundAnimation(array_get($data, 'animation'));

		if($image['animation'] === 'smooth') { 
			$image['speed'] 	= (int) array_get($data, 'speed');
			$image['direction'] = $this->getAnimationDirection(array_get($data, 'direction'));
		}

		return $image; 
	}


	public function fetchSlideBackground($data)
	{ 
		$slide = $this->getBackgroundColorization($data);  

		$slide['type'] 	= array_get($data, 'type');
		$slide['slide'] = array_get($data, 'slide');
		$slide['size'] 	= $this->getBackgroundImageSize(array_get($data, 'size'));
		$slide['top'] 	= (int) array_get($data, 'top');
		$slide['left'] 	= (int) array_get($data, 'left');
		$slide['time'] 	= (int) array_get($data, 'time');
		$slide['animation'] = $this->getBackgroundAnimation(array_get($data, 'animation'));

		if($slide['animation'] === 'smooth') { 
			$slide['animation'] === 'fixed'; 
		}

		return $slide; 
	}

	public function getBackgroundImageSize(string $size = null)
	{
		if(is_null($size) || ! in_array($size, ['initial', 'contain', 'cover'])) {
			$size = 'cover';
		}

		return $size;
	}

	public function getBackgroundAnimation(string $animation = null)
	{
		if(is_null($animation) || !in_array($animation, ['scroll', 'smooth', 'fixed'])) {
			$animation = 'fixed';
		}

		return $animation;
	}

	public function getAnimationDirection(string $direction = null)
	{
		if(! in_array($direction, ['l', 'r', 't', 'b', 'tr', 'br', 'tl', 'bl'])) {
			$direction = 't';
		}

		return $direction;
	}
}
