<?php 
namespace Core\Documentation\Http\Controllers;

use BinaryTorch\LaRecipe\Http\Controllers\DocumentationController as Controller;
use Core\Documentation\DocumentationRepository;

class DocumentationController extends Controller
{ 

    /**
     * DocumentationController constructor.
     */
    public function __construct(DocumentationRepository $documentationRepository)
    {
        $this->documentationRepository = $documentationRepository; 

        $this->middleware(['auth:admin']);
    }
}
