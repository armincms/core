<?php 
namespace Core\Extension\Repository;
  
use Core\Repository\ArminCacheableRepository; 

class ExtensionRepository extends ArminCacheableRepository 
{ 
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "Core\\Content\\Models\\Content";
    }
// end of class
}