<?php

namespace Core\App;

use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class AppLog extends Model
{ 
	use Metable;

	protected $guarded = [];
}
