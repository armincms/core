<?php 
return [
    'accepted' => [
        'jpg', 'png', 'jpeg'
    ],
	'schemas' => [ 
        'main' => [
            'group'         => '*', // group of usage
            'name'          => 'main', // unique name
            'resize'        => 'crop', // resize type
            'width'         => 720,
            'height'        => 480,
            'position'      => 'center', // crop postiion anchor
            'upsize'        => false, // cutting type
            'compress'      => 75,
            'extension'     => null, // save extension
            'placeholder'   => image_placeholder(720, 480),
        ],
        'thumbnail' => [
            'group'         => '*', // group of usage
            'name'          => 'thumbnail', // unique name
            'resize'        => 'crop', // resize type
            'width'         => 320,
            'height'        => 190,
            'position'      => 'center', // crop postiion anchor
            'upsize'        => false, // cutting type
            'compress'      => 75,
            'extension'     => null, // save extension
            'placeholder'   => image_placeholder(320, 190),
        ],
        'icon' => [
            'group'         => '*', // group of usage
            'name'          => 'icon', // unique name
            'resize'        => 'crop', // resize type
            'width'         => 50,
            'height'        => 50,
            'position'      => 'center', // crop postiion anchor
            'upsize'        => false, // cutting type
            'compress'      => 75,
            'extension'     => null, // save extension
            'placeholder'   => image_placeholder(50, 50),
        ],
	],
];