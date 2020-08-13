<?php 
namespace Core\Module\Contracts;

use Core\Contracts\Extensible;

interface Module extends Extensible
{
    /**
     * Display module data.
     * 
     * @return string
     */
    public function toHtml() : string;
}
