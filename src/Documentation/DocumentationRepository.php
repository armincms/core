<?php
namespace Core\Documentation;

use BinaryTorch\LaRecipe\DocumentationRepository as Repository;

class DocumentationRepository extends Repository
{   

    /**
     * DocumentationController constructor.
     *
     * @param Documentation $documentation
     */
    public function __construct(Documentation $documentation)
    { 
        parent::__construct($documentation);

        $this->docsRoute = 'panel/docs';
        $this->defaultVersion = config('larecipe.versions.default');
        $this->publishedVersions = config('larecipe.versions.published');
        $this->defaultVersionUrl = $this->docsRoute.'/'.$this->defaultVersion;
    } 
}
