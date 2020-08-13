<?php 
namespace Core\Document;

use Illuminate\Support\Manager;

class DocumentManager extends Manager
{     
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
    	return 'html';
    }

    public function createHtmlDriver()
    {
    	return app(HtmlDocument::class);
    }
}
