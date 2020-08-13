<?php 

if (! function_exists('none_image')) { 
    /**
     * Mkae image place holder.
     *
     * @param  integer  $width
     * @param  integer  $height
     * @param  string   $text
     * @return string
     */
    function none_image($width = 150, $height = 150, $text = 'none image')
    {        
        return "http://www.placehold.it/{$width}x{$height}/EFEFEF/AAAAAA&amp;text="
                . preg_replage('/\s/', '+', $text);
    }
}

if (! function_exists('is_true')) { 
    /**
     * Get the path to the templates folder.
     *
     * @param  string  $path
     * @return string
     */
    function is_true($string = '')
    {      
        if(blank($string)) {
            return false;
        } 

        return !($string === 'false' || $string === false || $string === 0 || $string === '0');
    }
}

if (! function_exists('dot_to_name')) { 
    /**
     * Get the path to the templates folder.
     *
     * @param  string  $path
     * @return string
     */
    function dot_to_name(string $dot = null)
    {   
    	if ($parts = explode('.', $dot)) { 

	 		$name= '';

	 		foreach ($parts as $key => $part) {
	 			$name .= $key > 0 ? "[{$part}]" : $part;
	 		}

	        return $name;
    	} 

    	return $dot;
    }
}

if (! function_exists('dot_to_id')) { 
    /**
     * Get the path to the templates folder.
     *
     * @param  string  $path
     * @return string
     */
    function dot_to_id(string $dot = null)
    {     
        return str_slug(preg_replace('/\./', '-', trim($dot, '.')));
    }
}

if (! function_exists('minify_css')) { 
    /**
     * Minifi css file from github. 
     *
     * @param  string  $path
     * @return string
     */
    function minify_css($css)
    {     
        if(class_exists(\MatthiasMullie\Minify\CSS::class)) {
            return (new \MatthiasMullie\Minify\CSS($css))->minify();
        }  
        
        return trim( $css ); 
    }
}

if (! function_exists('minify_js')) { 
    /**
     * Minifi js file.
     *
     * @param  string  $path
     * @return string
     */
    function minify_js($js)
    {      
        if(class_exists(\MatthiasMullie\Minify\JS::class)) {
            return (new \MatthiasMullie\Minify\JS($js))->minify();
        } 

        return preg_replace('/^\/\/[^\n]+/', "\n", $js);
    }
}

if (! function_exists('minify_html')) { 
    /**
     * Minifi html file.
     *
     * @param  string  $path
     * @return string
     */
    function minify_html($html)
    {     
    	if(trim($html) === "") return $html;
        // Remove extra white-space(s) between HTML attribute(s)
        $html = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $html));
        // Minify inline CSS declaration(s)
        if(strpos($html, ' style=') !== false) {
            $html = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
                return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
            }, $html);
        }
        if(strpos($html, '</style>') !== false) {
          $html = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
            return '<style' . $matches[1] .'>'. minify_css($matches[2]) . '</style>';
          }, $html);
        }
        if(strpos($html, '</script>') !== false) {
          $html = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
            return '<script' . $matches[1] .'>'. minify_js($matches[2]) . '</script>';
          }, $html);
        }
        return preg_replace(
            array(
                // t = text
                // o = tag open
                // c = tag close
                // Keep important white-space(s) after self-closing HTML tag(s)
                '#<(img|html)(>| .*?>)#s',
                // Remove a line break and two or more white-space(s) between tag(s)
                '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                '#<(img|html)(>| .*?>)<\/\1>#s', // reset previous fix
                '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
                // Remove HTML comment(s) except IE comment(s)
                '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
            ),
            array(
                '<$1$2</$1>',
                '$1$2$3',
                '$1$2$3',
                '$1$2$3$4$5',
                '$1$2$3$4$5$6$7',
                '$1$2$3',
                '<$1$2',
                '$1 ',
                '$1',
                ""
            ),
        $html);
    }
}