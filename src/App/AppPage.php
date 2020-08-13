<?php

namespace Core\App;

use Illuminate\Database\Eloquent\Model;

class AppPage extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'image' => 'collection',
    ];
}
