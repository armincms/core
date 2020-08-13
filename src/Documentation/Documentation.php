<?php 
namespace Core\Documentation;

use BinaryTorch\LaRecipe\Models\Documentation as Model;


class Documentation extends Model
{ 


    /**
     * Get the documentation index page.
     *
     * @param  string  $version
     * @return string
     */
    public function getIndex($version)
    {
        $closure = function () use ($version) {
            $path = base_path(__DIR__.'/reources/'.$version.'/index.md');

            if ($this->files->exists($path)) {
                $parsedContent = $this->parse($this->files->get($path));

                return $this->replaceLinks($version, $parsedContent);
            }

            return null;
        };

        if (! config('larecipe.cache.enabled')) {
            return $closure();
        }

        $cacheKey = 'larecipe.admin-docs.'.$version.'.index';
        $cachePeriod = config('larecipe.cache.period');

        return $this->cache->remember($cacheKey, $cachePeriod, $closure);
    }

    /**
     * Get the given documentation page.
     *
     * @param  string  $version
     * @param  string  $page
     * @return string
     */
    public function get($version, $page, $data = [])
    {
        $closure = function () use ($version, $page, $data) {
            $path = base_path(__DIR__.'/resources/'.$version.'/'.$page.'.md');

            if ($this->files->exists($path)) {
                $content = $this->parse($this->files->get($path));

                $parsedContent = $this->replaceLinks($version, $content);

                return $this->renderBlade($parsedContent, $data);
            }

            return null;
        };

        if (! config('larecipe.cache.enabled')) {
            return $closure();
        }

        $cacheKey = 'larecipe.admin-docs.'.$version.'.'.$page;
        $cachePeriod = config('larecipe.cache.period');

        return $this->cache->remember($cacheKey, $cachePeriod, $closure);
    }

    /**
     * Replace the version and route placeholders.
     *
     * @param  string  $version
     * @param  string  $content
     * @return string
     */
    public static function replaceLinks($version, $content)
    {
        $content = str_replace('{{version}}', $version, $content);

        $content = str_replace('{{route}}','panel/docs', $content);

        return $content;
    }

    /**
     * Check if the given section exists.
     *
     * @param  string  $version
     * @param  string  $page
     * @return bool
     */
    public function sectionExists($version, $page)
    {
        return $this->files->exists(
            base_path(__DIR__.'/resource/'.$version.'/'.$page.'.md')
        );
    }
}