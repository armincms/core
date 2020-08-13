<?php 
return [   
	'slugs' => [
		'%id%' =>	[
			'length' 	=> 1, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'id'],  
			'conditions'=> '[0-9]+',
		],  
		'%name%' =>	[
			'length' 	=> 1, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'name'],
			'conditions'=> '[-\pL\pN\s/]+',  
		],  
		'%fulldate%' =>	[ 
			'length'	=> 3, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'fulldate'], 
			'conditions'=> ['[0-9]{4}', '[0-9]{2}', '[0-9]{2}'], 
		],  
		'%shortdate%' =>	[
			'length' 	=> 2, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'shortdate'], 
			'conditions'=> [ '[0-9]{4}', '[0-9]{2}'], 
		], 
		'%stringdate%' =>	[
			'length' 	=> 3, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'stringdate'],
			'conditions'=> '[a-zA-Z0-9]+', 
		],   
		'%year%' =>	[
			'length' 	=> 1, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'year'],
			'conditions'=> '[0-9]{4}', 
		],   
		'%month%' =>	[
			'length' 	=> 1, 
			'mutator' 	=> ['Core\HttpSite\Helpers\SlugMutator', 'month'],
			'conditions'=> '[0-9]{2}',
		],    
	],
];