<?php 
namespace Core\App\Repository;
  
use Core\Repository\ArminCacheableRepository; 

class AppPageRepository extends ArminCacheableRepository 
{ 
	function model()
	{
		return 'Core\App\AppPage';
	}
}