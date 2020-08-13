{!! 
	Field::text()
	->text(function ($input) {
		$input->class('full-width');
	})
	->text(function($txt) {
		$txt->name('setting.genral.config');
	})
	->label(function ($label) {
		$label->order(0);
	})
	->append('button', function ($button) {  
		$button->id('s');   
		$button->class('s');   
		$button->class('s');   
	}) 
	->append('button')
	->radio(function ($radio) {
		$radio->uncheck();
	})
!!}