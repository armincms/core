<?php
use Jenssegers\Agent\Agent;

if (! function_exists('template_hint')) { 
    /**
     * Namespace for template files.
     *
     * @param  string  $path
     * @return string
     */
    function template_hint(string $template, string $directory = null)
    { 
        return call_user_func('template_hint_key', $template, $directory); 
    }
}

if (! function_exists('template_hint_key')) { 
    /**
     * Namespace for template files.
     *
     * @param  string  $path
     * @return string
     */
    function template_hint_key(string $template, string $directory = null)
    { 
        return "tmp-{$template}". ($directory? "::{$directory}" : ''); 
    }
}

if (! function_exists('template_path')) { 
    /**
     * Get the path to the templates folder.
     *
     * @param  string  $path
     * @return string
     */
    function template_path($template = '')
    { 
        return extension_path("templates").($template ? DS.$template : $template);
    }
}

if (! function_exists('default_template')) { 
    /**
     * Get or Set default template.
     *
     * @param  string|null  $template
     * @return string
     */
    function default_template(string $template = null)
    { 
        if(is_null($template)) {
            return option('_default_template', 'default');
        }

        return option()->{'_default_template'} = $template; 
    }
}

if (! function_exists('active_template')) { 
    /**
     * Return current active template.
     *
     * @param  string  $path
     * @return string
     */
    function active_template()
    {   
        return default_template();
        global $templates;

        $locale = language(\App::getLocale());

        if(! isset($templates)) { 
            $templates = app('armin.template')->get(true)->where(
                'language_id', @$locale->id
            );
        } 

        return $templates->sortBy(function($template) { 
            $active = (int) strtotime($template->publish_start)?:(int)$template->isDefault();

            return time() + $active;
        })->pop();
    }
}  

if (! function_exists('responsive_class')) { 
    /**
     * Return responsive class name.
     *
     * @param  string  $column
     * @param  string  $base
     * @return string
     */
    function responsive_class($column, $base)
    {  
        $modulo = max_modulo($base, $column);

        return round($column)/$modulo ."-" . round($base)/$modulo;
    }
}  

if (! function_exists('agent_reponsive_size')) { 
    /**
     * Return responsive class name.
     *
     * @param  string  $column
     * @param  string  $base
     * @return string
     */
    function agent_reponsive_size()
    {  
        $agent = new Agent;

        if($agent->isDesktop()) {
            return 'dl';
        }

        if($agent->isTablet()) {
            return 'tp';
        }

        return 'mp'; 
    }
}  

if (! function_exists('max_modulo')) { 
    /**
     * Return responsive class name.
     *
     * @param  string  $column
     * @param  string  $base
     * @return string
     */
    function max_modulo($num1, $num2)
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
}  

if (! function_exists('theme_facilities')) { 
    /**
     * Return availabe facility of theme facilities.
     * 
     * @param  string $facility
     * @return \Illuminate\Support\Collection
     */
    function theme_facilities(string $facility)
    { 
        return collect((array) config("armin.template.{$facility}", []));
    }
}  

if (! function_exists('theme_facility')) { 
    /**
     * Return availabe facility colors.
     * 
     * @param  string  $facility
     * @param  string  $name
     * @return \Illuminate\Support\Collection
     */
    function theme_facility(string $facility, string $name)
    { 
        return collect(theme_facilities($facility)->where('name', $name)->first());
    }
}  

if (! function_exists('theme_colors')) { 
    /**
     * Return availabe theme colors.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_colors()
    { 
        return theme_facilities('colors');
    }
}  

if (! function_exists('theme_color')) { 
    /**
     * Return availabe theme colors.
     * 
     * @param  string  $color
     * @return \Illuminate\Support\Collection
     */
    function theme_color(string $color)
    { 
        return theme_facility('colors', $color);
    }
}  

if (! function_exists('theme_patterns')) { 
    /**
     * Return availabe theme patterns.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_patterns()
    { 
        return theme_facilities('patterns');
    }
}  

if (! function_exists('theme_pattern')) { 
    /**
     * Return availabe theme patterns.
     * 
     * @param  string  $pattern
     * @return \Illuminate\Support\Collection
     */
    function theme_pattern(string $pattern)
    { 
        return theme_facility('patterns', $pattern);
    }
}  
if (! function_exists('theme_images')) { 
    /**
     * Return availabe theme images.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_images()
    { 
        return theme_facilities('images');
    }
}  

if (! function_exists('theme_image')) { 
    /**
     * Return availabe theme images.
     * 
     * @param  string  $image
     * @return \Illuminate\Support\Collection
     */
    function theme_image(string $image)
    { 
        return theme_facility('images', $image);
    }
}  

if (! function_exists('theme_slides')) { 
    /**
     * Return availabe theme slides.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_slides()
    { 
        return theme_facilities('slides');
    }
}  

if (! function_exists('theme_slide')) { 
    /**
     * Return availabe theme slides.
     * 
     * @param  string  $slide
     * @return \Illuminate\Support\Collection
     */
    function theme_slide(string $slide)
    { 
        return theme_facility('slides', $slide);
    }
}  

if (! function_exists('theme_svgs')) { 
    /**
     * Return availabe theme svgs.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_svgs()
    { 
        return theme_facilities('svgs');
    }
}  

if (! function_exists('theme_svg')) { 
    /**
     * Return availabe theme svgs.
     * 
     * @param  string  $svg
     * @return \Illuminate\Support\Collection
     */
    function theme_svg(string $svg)
    { 
        return theme_facility('svgs', $svg);
    }
}  
if (! function_exists('theme_fonts')) { 
    /**
     * Return availabe theme fonts.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_fonts()
    { 
        return theme_facilities('fonts');
    }
}  

if (! function_exists('theme_font')) { 
    /**
     * Return availabe theme fonts.
     * 
     * @param  string  $font
     * @return \Illuminate\Support\Collection
     */
    function theme_font(string $font)
    { 
        return theme_facility('fonts', $font);
    }
}  

if (! function_exists('theme_videos')) { 
    /**
     * Return availabe theme videos.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_videos()
    { 
        return theme_facilities('videos');
    }
}  

if (! function_exists('theme_video')) { 
    /**
     * Return availabe theme videos.
     * 
     * @param  string  $video
     * @return \Illuminate\Support\Collection
     */
    function theme_video(string $video)
    { 
        return theme_facility('videos', $video);
    }
}  

if (! function_exists('theme_classes')) { 
    /**
     * Return availabe theme classes.
     * 
     * @return \Illuminate\Support\Collection
     */
    function theme_classes()
    { 
        return theme_facilities('classes');
    }
}  

if (! function_exists('theme_class')) { 
    /**
     * Return availabe theme classes.
     * 
     * @param  string  $class
     * @return \Illuminate\Support\Collection
     */
    function theme_class(string $class)
    { 
        return theme_facility('classes', $class);
    }
}  
