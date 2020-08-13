<?php 
namespace Core\Armin\Http\Controllers;

use App\Http\Controllers\Controller; 
use Core\Crud\Actions\Save;
use Core\Armin\Forms\SettingForm;
use Illuminate\Http\Request;

class SettingController extends Controller
{ 
	public function edit()
	{ 
		return view('admin-crud::edit') 
    				->withForm($this->form())
    				->withName('general-setting')
                    ->withTitle('armin::title.general_setting')
                    ->withActions(collect([new Save()]));
	}

	public function update()
	{
		$this->form()->save(function($data) { 
			$data->get('general')->each(function($value, $key) {
				option()->$key = $value;
			});
		});

		$this->makeConfigurationFile();

		\Artisan::call('config:cache');

		return back()->withMessages(armin_trans('successfully_saved'));
	}

	public function makeConfigurationFile()
	{
		$config = [
			'log'      => 'daily',
			'locale'   => option('_default_locale', 'fa'),
			'timezone' => option('_timezone', 'Asia/Tehran'),
			'url'      => option('_base_domain', config('app.url')),
			'api_domain'      => option('_api_domain', config('app.url')),
		]; 

		ob_start();
		var_export($config); 

		\File::put(dirname(dirname(__DIR__)).DS.'config.php', '<?php return ' .ob_get_clean(). ';');
	}

	public function maintenance(Request $request)
	{ 
		\Artisan::call((bool) $request->get('maintenance')  ? 'down' : 'up');

		return ['maintenance' => (bool) $request->get('maintenance')];
	}

	public function form()
	{
		return new SettingForm;
	}

}
