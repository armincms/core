<?php 
namespace Core\Armin\Forms; 

use Core\Crud\Forms\MultilingualResourceForm; 
use Core\Crud\Concerns\HasSeo;   

class SettingForm extends MultilingualResourceForm 
{    

	protected $name = 'setting';

	public function build()
	{         
		 $this
		 	->raw('<div class=columns><div class=four-columns>')
		 	->field('select', '_default_site', false, 'armin::title.default_site', $this->getSites(), option('_default_site', 'home'))
		 	->field('select', '_default_locale', false, 'armin::title.default_locale', language()->pluck('title', 'name')->all(), option('_default_locale', 'fa'))
		 	->field('select', '_url_locale', false, 'armin::title.url_locale', [
		 		'name' => 'name',
		 		'international' => 'international'
		 	], option('_url_locale', 'name'))
		 	->field('select', '_timezone', false, 'armin::title.timezone', $this->getTimezones(), option('_timezone', 'Asia/Tehran'))
		 	->field('select', '_admin_login', false, 'armin::title.admin_login', $this->getLoginTypes(), option('_admin_login', 'username'))
		 	->field('select', '_user_login', false, 'armin::title.user_login', $this->getLoginTypes(), option('_user_login', 'username'))  
		 	// ->field('select', '_registered_user_role', false, 'armin::title.registered_user_role', $this->getRoles(), option('_registered_user_role', 'guest')) 
		 	// ->field('select', '_guest_user_role', false, 'armin::title.guest_user_role', $this->getRoles(), option('_guest_user_role', 'guest')) 
		 	// ->field('select', '_default_access', false, 'armin::title.default_access', $this->getRoles(), option('_default_access', 'public')) 
		 	->field('select', '_captcha', false, 'armin::title.captcha', $this->getCaptchas(), option('_captcha', 'google'))
		 	->raw('</div><div class=four-columns>') 
		 	->field('text', '_base_domain', false, 'armin::title.base_domain', null, option('_base_domain', config('app.url')), ['dir' => 'ltr', 'style' => 'text-align:left'], [], null, 'url')
		 	->field('text', '_api_domain', false, 'armin::title.api_doamin', null, option('_api_domain', config('app.url').'/api'), ['dir' => 'ltr', 'style' => 'text-align:left'], [], null, 'url')
		 	->field('text', '_site_title', true, 'armin::title.title', null, $this->fakeModel('_site_title'))
		 	->field('textarea', '_site_description', true, 'armin::title.description', null, $this->fakeModel('_site_description')) 
		 	->field('textarea', '_site_tags', true, 'armin::title.tags', null, $this->fakeModel('_site_tags'), [], [], armin_trans('armin::title.split_with_comma')) 
		 	->raw('</div><div class=four-columns>') 
		 	->field('switch', '_maintenance', false, 'armin::title.maintenance', 1, 0, app()->isDownForMaintenance())
		 	->field('switch', '_gzip', false, 'armin::title.gzip', 1, 0, option('_gzip', 1))
		 	->field('switch', '_force_www', false, 'armin::title.force_www', 1, 0, option('_force_www', 1))
		 	->pushScript('maintenance', view('armin::maintenance'))
		 	->raw('</div></div>'); 
	}    

	public function getLoginTypes()
	{
		return [
			'username' 	=> armin_trans('user-management::title.username'), 
			'email'		=> armin_trans('user-management::title.email'), 
			'mobile'	=> armin_trans('user-management::title.mobile'), 
		];		
	}

	public function getSites()
	{
		return collect(app('site')->all())->mapWithKeys(function($site) {
			$name = $site->name();
			$title = title_case($site->title() ?: $name);
			return [$name => $title]; 
		})->all();
	}

	public function getTimezones()
	{
		$timezones = [
			'Asia/Kabul', 'Asia/Tehran', 'Europe/Tirane', 'Africa/Algiers', 'Europe/Andorra', 
			'Africa/Luanda', 'America/Anguilla', 'Antarctica/Casey', 'Antarctica/Davis', 
			'Antarctica/DumontDUrville', 'Antarctica/Mawson', 'Antarctica/McMurdo', 
			'Antarctica/Palmer', 'Antarctica/Rothera', 'Antarctica/Syowa', 'Antarctica/Troll', 
			'Antarctica/Vostok', 'America/Antigua', 'America/Argentina/Buenos', 
			'America/Argentina/Catamarca', 'America/Argentina/Cordoba', 'America/Argentina/Jujuy', 
			'America/Argentina/La', 'America/Argentina/Mendoza', 'America/Argentina/Rio', 
			'America/Argentina/Salta', 'America/Argentina/San', 'America/Argentina/San', 
			'America/Argentina/Tucuman', 'America/Argentina/Ushuaia', 'Asia/Yerevan', 
			'America/Aruba', 'Antarctica/Macquarie', 'Australia/Adelaide', 'Australia/Brisbane', 
			'Australia/Broken', 'Australia/Currie', 'Australia/Darwin', 'Australia/Eucla', 
			'Australia/Hobart', 'Australia/Lindeman', 'Australia/Lord', 'Australia/Melbourne', 
			'Australia/Perth', 'Australia/Sydney', 'Europe/Vienna', 'Asia/Baku', 'America/Nassau', 
			'Asia/Bahrain', 'Asia/Dhaka', 'America/Barbados', 'Europe/Minsk', 'Europe/Brussels', 
			'America/Belize', 'Africa/Porto-Novo', 'Atlantic/Bermuda', 'Asia/Thimphu', 'America/La',
			'America/Kralendijk', 'Europe/Sarajevo', 'Africa/Gaborone', 'America/Araguaina', 
			'America/Bahia', 'America/Belem', 'America/Boa', 'America/Campo', 'America/Cuiaba', 
			'America/Eirunepe', 'America/Fortaleza', 'America/Maceio', 'America/Manaus', 
			'America/Noronha', 'America/Porto', 'America/Recife', 'America/Rio', 'America/Santarem', 
			'America/Sao', 'America/Tortola', 'Asia/Brunei', 'Europe/Sofia', 'Africa/Bujumbura', 
			'Asia/Phnom', 'Africa/Douala', 'America/Atikokan', 'America/Blanc-Sablon', 
			'America/Cambridge', 'America/Creston', 'America/Dawson', 'America/Dawson', 
			'America/Edmonton', 'America/Fort', 'America/Glace', 'America/Goose', 'America/Halifax', 
			'America/Inuvik', 'America/Iqaluit', 'America/Moncton', 'America/Nipigon', 
			'America/Pangnirtung', 'America/Rainy', 'America/Rankin', 'America/Regina', 
			'America/Resolute', 'America/St', 'America/Swift', 'America/Thunder', 'America/Toronto', 
			'America/Vancouver', 'America/Whitehorse', 'America/Winnipeg', 'America/Yellowknife', 
			'Africa/Bangui', 'Africa/Ndjamena', 'America/Punta', 'America/Santiago', 
			'Pacific/Easter', 'Asia/Shanghai', 'Asia/Urumqi', 'America/Bogota', 'Indian/Comoro', 
			'Europe/Zagreb', 'America/Havana', 'America/Curacao', 'Asia/Famagusta', 'Asia/Nicosia', 
			'Africa/Kinshasa', 'Africa/Lubumbashi', 'Europe/Copenhagen', 'Africa/Djibouti', 
			'America/Dominica', 'Republic', 'America/Guayaquil', 'Pacific/Galapagos', 
			'Africa/Cairo', 'Salvador', 'Guinea', 'Africa/Asmara', 'Europe/Tallinn', 'Africa/Addis', 
			'Pacific/Fiji', 'Europe/Helsinki', 'Europe/Paris', 'Guiana', 'Polynesia', 'Polynesia', 
			'Polynesia', 'Indian/Kerguelen', 'Africa/Libreville', 'Africa/Banjul', 'Asia/Tbilisi', 
			'Europe/Berlin', 'Europe/Busingen', 'Africa/Accra', 'Europe/Gibraltar', 'Europe/Athens', 
			'America/Danmarkshavn', 'America/Godthab', 'America/Scoresbysund', 'America/Thule', 
			'America/Grenada', 'America/Guadeloupe', 'Pacific/Guam', 'America/Guatemala', 
			'Europe/Guernsey', 'Africa/Conakry', 'Africa/Bissau', 'America/Guyana', 
			'America/Port-au-Prince', 'America/Tegucigalpa', 'Europe/Budapest', 
			'Atlantic/Reykjavik', 'Asia/Kolkata', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Makassar', 
			'Asia/Pontianak', 'Asia/Tehran', 'Asia/Baghdad', 'Europe/Dublin', 'Europe/Isle', 
			'Asia/Jerusalem', 'America/Jamaica', 'Asia/Tokyo', 'Europe/Jersey', 'Asia/Amman', 
			'Asia/Almaty', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Atyrau', 'Asia/Oral', 'Asia/Qostanay', 
			'Asia/Qyzylorda', 'Africa/Nairobi', 'Pacific/Enderbury', 'Pacific/Kiritimati', 
			'Pacific/Tarawa', 'Asia/Kuwait', 'Asia/Bishkek', 'Asia/Vientiane', 'Europe/Riga', 
			'Asia/Beirut', 'Africa/Maseru', 'Africa/Monrovia', 'Africa/Tripoli', 'Europe/Vaduz', 
			'Europe/Vilnius', 'Europe/Luxembourg', 'Asia/Macau', 'Europe/Skopje', 
			'Indian/Antananarivo', 'Africa/Blantyre', 'Asia/Kuala', 'Asia/Kuching', 
			'Indian/Maldives', 'Africa/Bamako', 'Europe/Malta', 'America/Martinique', 
			'Africa/Nouakchott', 'Indian/Mauritius', 'Indian/Mayotte', 'America/Bahia', 
			'America/Cancun', 'America/Chihuahua', 'America/Hermosillo', 'America/Matamoros', 
			'America/Mazatlan', 'America/Merida', 'America/Mexico', 'America/Monterrey', 
			'America/Ojinaga', 'America/Tijuana', 'Pacific/Chuuk', 'Pacific/Kosrae', 
			'Pacific/Pohnpei', 'Europe/Chisinau', 'Europe/Monaco', 'Asia/Choibalsan', 
			'Asia/Hovd', 'Asia/Ulaanbaatar', 'Europe/Podgorica', 'America/Montserrat', 
			'Africa/Casablanca', 'Africa/Maputo', 'Asia/Yangon', 'Africa/Windhoek', 
			'Pacific/Nauru', 'Asia/Kathmandu', 'Europe/Amsterdam', 'America/Managua', 
			'Africa/Niamey', 'Africa/Lagos', 'Pacific/Niue', 'Pacific/Saipan', 'Europe/Oslo', 
			'Asia/Karachi', 'Pacific/Palau', 'America/Panama', 'Pacific/Bougainville', 
			'Pacific/Port', 'America/Asuncion', 'America/Lima', 'Asia/Manila', 'Pacific/Pitcairn', 
			'Europe/Warsaw', 'Atlantic/Azores', 'Atlantic/Madeira', 'Europe/Lisbon', 'Asia/Qatar', 
			'Indian/Reunion', 'Europe/Bucharest', 'Asia/Anadyr', 'Asia/Barnaul', 'Asia/Chita', 
			'Asia/Irkutsk', 'Asia/Kamchatka', 'Asia/Khandyga', 'Asia/Krasnoyarsk', 'Asia/Magadan', 
			'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Sakhalin', 
			'Asia/Srednekolymsk', 'Asia/Tomsk', 'Asia/Ust-Nera', 'Asia/Vladivostok', 'Asia/Yakutsk', 
			'Asia/Yekaterinburg', 'Europe/Astrakhan', 'Europe/Kaliningrad', 'Europe/Kirov', 
			'Europe/Moscow', 'Europe/Samara', 'Europe/Saratov', 'Europe/Simferopol', 
			'Europe/Ulyanovsk', 'Europe/Volgograd', 'Africa/Kigali', 'America/St', 'Pacific/Apia', 
			'Africa/Dakar', 'Europe/Belgrade', 'Indian/Mahe', 'Asia/Singapore', 'Europe/Bratislava', 
			'Europe/Ljubljana', 'Africa/Mogadishu', 'Atlantic/South', 'Africa/Ceuta', 
			'Atlantic/Canary', 'Europe/Madrid', 'Africa/Khartoum', 'America/Paramaribo', 
			'Africa/Mbabane', 'Europe/Stockholm', 'Europe/Zurich', 'Asia/Damascus', 'Asia/Taipei', 
			'Asia/Dushanbe', 'Africa/Dar', 'Asia/Bangkok', 'Africa/Lome', 'Pacific/Fakaofo', 
			'Pacific/Tongatapu', 'America/Port', 'Africa/Tunis', 'Europe/Istanbul', 'Asia/Ashgabat', 
			'Pacific/Funafuti', 'America/St', 'Africa/Kampala', 'Europe/Kiev', 'Europe/Uzhgorod', 
			'Europe/Zaporozhye', 'Asia/Dubai', 'Pacific/Midway', 'Pacific/Wake', 
			'America/Montevideo', 'Asia/Samarkand', 'Asia/Tashkent', 'Pacific/Efate', 
			'Europe/Vatican', 'America/Caracas', 'Pacific/Wallis', 'Asia/Aden', 'Africa/Lusaka',  
			'Asia/Muscat', 'Africa/Harare'
		];

		return collect($timezones)->combine($timezones)->sort()->all();		
	}

	public function getRoles()
	{
		return \Core\User\Models\Role::get()->filter(function($role) {
			return ! in_array($role->name, ['admin', 'administrator', 'superadministrator']);
		})->pluck('display_name', 'name')->all();
	}

	public function getCaptchas()
	{
		return [
			'google' => 'google'
		];
	}

	public function fakeModel($name)
	{
		$model = $this->getModel(); 

		$data = collect(option($name))->mapWithKeys(function($vlaue, $key) use ($name) {
			return ["{$key}::{$name}" => $vlaue];
		})->merge($model->getAttributes())->toArray();

		$this->setModel($model->forceFill($data));
  
		return;
	}

	public function generalMap()
	{ 
		return [
			'_default_site', '_default_locale', '_url_locale', '_admin_login', '_user_login', 
			/*'_registered_user_role', '_guest_user_role', '_default_access',*/ '_captcha', '_base_domain', 
			'_api_domain', '_site_title', '_site_description', '_site_tags', '_maintenance', 
			'_gzip', '_force_www'
 		];
	}
	public function translateMap()
	{
		return [];
	}
	public function relationMap()
	{
		return [ 
		];
	}   

	public function getModel()
	{
		if(! isset($this->model)) {
			$this->model = new FakeModel;
		}

	 	return $this->model;
	} 
}
