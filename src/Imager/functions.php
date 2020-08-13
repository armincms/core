<?php 
if(! function_exists('imager_schema'))
{
	/**
	 * Find image schema's for specific group(s).
	 *
	 * @param  string|array $group
	 * @return array 
	 */
	function imager_schema($group)
	{
		return app('armin.imager.schema')->withGlobals($group)->pluck('name')->toArray();
	}
}

if(! function_exists('schema_placeholder'))
{
	/**
	 * Find schema's placeholder.
	 *
	 * @param  string $schema
	 * @return string 
	 */
	function schema_placeholder(string $schema)
	{
		return array_get(app('armin.imager.schema')->find($schema), 'placeholder');
	}
}

if(! function_exists('image_placeholder'))
{
	/**
	 * Make image placeholder.
	 *
	 * @param  int $width
	 * @param  int $height
	 * @param  string $text
	 * @param  string $background
	 * @param  string $color 
	 * @return string 
	 */
	function image_placeholder(int $width, int $height=null, string $text=null, string $background=null, string $color=null)
	{ 
		$height       = is_null($height)   ? $width            : $height;
		$text         = empty($text)       ? 'armindesign.com' : $text; 
		$background   = empty($background) ? 'efefef'          : $background; 
		$color        = empty($color)      ? '000'	           : $color; 

		return "https://via.placeholder.com/{$width}x{$height}/{$background}/{$color}?text={$text}";
	}
}