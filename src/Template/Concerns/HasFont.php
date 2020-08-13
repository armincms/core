<?php 
namespace Armincms\Template\Concerns;

use Illuminate\Support\HtmlString;

trait HasFont
{
	public function fontFields(string $name = 'font_facility', $colors = true, $attrs =[] , $wrap_attrs=[])
	{  
		$this   
            ->field('select', "{$name}[name]", false, 'template::title.font',  
                theme_fonts()->pluck('title', 'name')->prepend(
                	armin_trans('armin::title.select'), null
                )->all(), null, $attrs, [], [], $wrap_attrs
            );

           if(true === $colors) {
           		$this
		            ->field('select', "{$name}[color]", false, 'template::title.font_color',  
		                theme_colors()->pluck('title', 'name')->prepend(
		                	armin_trans('armin::title.select'), null
		                )->all(), null, $attrs, [], [], $wrap_attrs
		            )
		            ->field('select', "{$name}[link]", false, 'template::title.link_color',  
		                theme_colors()->pluck('title', 'name')->prepend(
		                	armin_trans('armin::title.select'), null
		                )->all(), null, $attrs, [], [], $wrap_attrs
		            )
		            ->field('select', "{$name}[hover]", false, 'template::title.hover_color',  
		                theme_colors()->pluck('title', 'name')->prepend(
		                	armin_trans('armin::title.select'), null
		                )->all(), null, $attrs, [], [], $wrap_attrs
		            ); 
           }

        return $this; 
	}  
}
