<?php 
namespace Core\User\Forms; 

use Core\Crud\Forms\ResourceForm;
use Core\Crud\Contracts\TabForm;
use Core\Crud\Concerns\HasImage;   

class UserManagementForm extends ResourceForm implements TabForm
{ 
	use HasImage;

	protected $uploadPath = 'user-management';

	protected $name = 'user-management';
	protected $title = 'user-management::title.user';

	public function build()
	{         
		$this
			->field('select', 'meta[fullname]', false, 'user-management::title.fullname', [
				'firstname'          => armin_trans('user-management::title.firstname'),
				'lastname'           => armin_trans('user-management::title.lastname'),
				'firstname_lastname' => armin_trans('user-management::title.firstname') .' , ' .armin_trans('user-management::title.lastname'),
				'lastname_firstname' => armin_trans('user-management::title.lastname') .' , ' .armin_trans('user-management::title.firstname'),
				'displayname'        => armin_trans('user-management::title.displayname'),
			], optional($this->model)->getMeta('fullname')?: 'firstname_lastname')
			->field('text', 'user_password', false, [
					'label' => 'user-management::title.password',
					'attributes' => ['class' => 'red label']
			], null, null, ['required'], [], null, 'password')
			->field('text', 'firstname', false, 'user-management::title.firstname')
			->field('text', 'lastname', false, 'user-management::title.lastname')
			->field('text', 'displayname', false, 'user-management::title.displayname')
			->field('text', 'username', false, 'user-management::title.username', null, null, ['required'] ,[])
			->field('text', 'email', false, 'user-management::title.email') 
			->field('switch', 'status', false, 'user-management::title.status', 'activated', 'pending')
			->child('profile', function($form) { 
				$form 
					->setTitle('user-management::title.profile')
					->raw('<div class=columns><div class=six-columns>')
					->field('groupCheckable', 'meta[gender]', false, 'user-management::title.gender', true, [
						'male' 		=> [
							'label' => 'user-management::title.male',
							'value' => 'male',
							'checked' => true
						],
						'female' 		=> [
							'label' => 'user-management::title.female',
							'value' => 'female',
							'checked'=>optional($this->getModel())->gender == 'female' 
						], 
					]) 
					->field('text', 'meta[mobile]', false, 'user-management::title.mobile', null, null, ['pattern' => '[0-9]{8,12}'])
					->field('text', 'meta[phone]', false, 'user-management::title.phone', null, null, ['pattern' => '[0-9]{8,12}']) 
					->raw('<h4 class="green underline">birthday</h4>') 
					->field('select', 'day', false, 'user-management::title.day', $this->numbers(1,32),
						$this->model ? (int) $this->model->safeBirthday()->format('d') : null
					) 
					->field('select', 'month', false, 'user-management::title.month', $this->numbers(1,13),
						$this->model ? (int) $this->model->safeBirthday()->format('m') : null
					) 
					->field('select', 'year', false, 'user-management::title.year', 
						$this->numbers(now()->format('Y') - 120, now()->format('Y') + 1),
						$this->model ? (int) $this->model->safeBirthday()->format('Y') : null 
					)
					->element('hidden', 'meta[birthday]')
					->raw('</div><div class=six-columns>')
					->imageUploader('meta[avatar]', false)
					->raw('</div></div>');
			})
			->child('password', function($form) {
				$form
					->setTitle('user-management::title.password')
					->field('text', 'password', false, 'user-management::title.password', null, null, [] ,[], null, 'password')
					->field('text', 'password_confirmation', false, 'user-management::title.password_confirmation', null, null, [] ,[], null, 'password');
			}); 
	}     
 	
	public function numbers($start, $end)
	{
		$numbers = [];

		for ($i = $start; $i < $end; $i++) {
			$numbers[$i] = $i;
		}

		return $numbers;
	}
 
 	public function transformUsername($username)
 	{
 		if(! empty($username)) {
 			return $username;
 		}

 		if($username = optional($this->getModel())->username) {
 			return $username;
 		} 

 		return $this->name . time(); 		
 	}

 	public function transformPassword($password)
 	{ 
 		$lastPassword = optional($this->getModel())->password;

 		if(request()->input('password_confirmation') !== $password) {
 			return $lastPassword;
 		}  

 		return empty($password)? $lastPassword : bcrypt($password); 
 	}

 	public function transformMeta($meta)
 	{ 
 		return collect($meta)->map(function($value, $key) {
 			switch ($key) {
 				case 'gender':
 					return $value === 'female' ? $value : 'male';  
 				case 'birthday':  
	 				return (string) \Carbon\Carbon::create(
	 					(int) $this->getInput('year', now()->format('Y')), 
	 					(int) $this->getInput('month', 1), 
	 					(int) $this->getInput('day', 1)
	 				);
 					break;
 				
 				default:
 					return $value;
 					break;
 			} 
 		})->toArray(); 
 	}

	public function generalMap()
	{ 
		return [
			'firstname', 'lastname', 'displayname', 'username', 'password', 'email', 'status'
		];
	}
	public function translateMap()
	{
		return [];
	}
	public function relationMap()
	{
		return [
			'meta' => 'sync'
		];
	}  

	public function schemas($name)
	{
		return imager_schema('user-management');
	}
 

}
