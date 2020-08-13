<?php 
namespace Core\Document;

use Illuminate\View\Engines\CompilerEngine as Engine; 
use Core\Document\Concerns\HasConfig; 

class CompilerEngine extends Engine
{ 
	use HasConfig;  
}
