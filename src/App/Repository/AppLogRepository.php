<?php 
namespace Core\App\Repository;
  
use Core\Repository\ArminCacheableRepository; 

class AppLogRepository extends ArminCacheableRepository 
{ 
	function model()
	{
		return 'Core\App\AppLog';
	}
}