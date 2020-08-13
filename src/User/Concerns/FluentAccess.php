<?php  
namespace Core\User\Concerns;
use Illuminate\Support\Carbon;
use Core\Crud\Concerns\HasCustomImage;

trait FluentAccess
{ 
	use HasCustomImage;

    public function fullname()
    {  
    	$fullname = "{$this->firstname} {$this->lastname}"; 

    	if($this->getMeta('fullname') == 'lastname_firstname') {
    		$fullname = "{$this->lastname} {$this->firstname}";
    	}

    	if(in_array($this->getMeta('fullname'), ['firstname', 'lastname', 'displayname'])) {
    		$fullname = $this->{$this->getMeta('fullname')};
    	}

    	return title_case($fullname); 
    }

    public function birthday()
    {
    	if(! is_null($this->getMeta('birthday'))) {  
    		return new Carbon($this->getMeta('birthday'));
    	}

    	return null;
    }

    public function safeBirthday()
    { 
    	return $this->birthday()?: now();
    }

    public function getAvatar()
    {
    	$images = $this->getImages('avatar')->first()?: collect();

    	return $images->get('avatar', '/admin/rtl/img/user.png'); 
    }

    public function getOriginal($key = null, $default = null)
    { 
        if(starts_with($key, 'meta')) {
            return $this->getOriginal(str_after($key, 'meta.'), $default);
        } 

    	if($value = $this->getMeta($key) && ! is_null($key)) {  
    		return $value;
    	}

    	return parent::getOriginal($key, $value);
    }
}
