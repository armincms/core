<?php 
namespace Core\Menu\Repository;
  
use Core\Repository\ArminCacheableRepository; 

class MenuRepository extends ArminCacheableRepository 
{ 
    protected $cacheExcept = []; 

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "Core\\Menu\\Menu";
    }
// end of class
}