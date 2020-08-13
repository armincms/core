<?php 
namespace Core\Installer\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan; 
use Illuminate\Support\Facades\Auth;
use Core\User\Models\Admin;
use Core\HttpSite\UrlHelper;
use Exception;
  
/**
* 
*/
class Installer extends Controller
{
	protected $errors = [];

	/**
	 * show installer wizard form
	 * @param  Illuminate\Hhttp\Request $request 
	 * @return Illuminate\Support\Facades\View view()
	 */
	public function configuration(Request $request)
	{
		Artisan::call('config:clear');

	 	return view('installer::configuration');
	}

	public function configured(Request $request)
	{
		$satisfied = (version_compare(PHP_VERSION, '7.1.3') >= 0)
				&& extension_loaded('openssl') 
				&& extension_loaded('PDO')
				&& extension_loaded('PDO_MySql')
				&& extension_loaded('Mbstring')
				&& extension_loaded('Tokenizer')
				&& extension_loaded('Xml')
				&& extension_loaded('json')
				&& extension_loaded('Ctype')
				&& extension_loaded('BCMath');

		return $satisfied ? redirect()->route('installer.migrate') : back()->withErrors([
			'Please check your server configuration'
		]);
	}

	/**
	 * test of db information
	 * @param  Illuminate\Hhttp\Request $request 
	 * @return Illuminate\Http\Response response()
	 */
	public function migrate(Request $request)
	{ 
	 	return view('installer::dbase'); 
	}
	/**
	 * install bse database of system
	 * @param  Illuminate\Http\Request $request 
	 * @return response()
	 */
	public function migrated(Request $request)
	{ 	   
		$this->validate($request, [
			'DB_PREFIX' => 'nullable|regex:/^[^0-9].*$/'
		], ['پیشوند جدول باید با حروف شروع شود']); 

		if($this->checkDatabaseConnection($request)) {

			try {     
	 			Artisan::call('migrate', ['--force' => true]); 
			} catch (Exception $e) {
				return back()->withErrors([$e->getMessage()]);
			}

			return redirect()->route('installer.dbseed'); 
		} 

		return back()->withErrors($this->mergeErrors(['Check your databse configuration']));
	}

	public function seed(Request $request)
	{
	 	return view('installer::dbseed'); 
	}

	public function dbseed(Request $request)
	{  
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required|confirmed|min:8', 
		]); 

		try {
 			Artisan::call('db:seed'); 
		} catch (Exception $e) {
 			Artisan::call('migrate:refresh'); 
			return back()->withErrors([$e->getMessage()] + ['Please Clean Your Database']);
		} 
 
		Admin::where('username', 'administrator')->update([
			'username' => $request->input('username'),
			'password' => bcrypt($request->input('password'))
		]);

		Artisan::call('key:generate');
		Artisan::call('view:clear');
		Artisan::call('cache:clear');  

		return redirect()->route('installer.login');
	}

	public function showLoginForm()
	{ 
		return view('installer::login');
	}


	public function login(Request $request)
	{
		if(Auth::guard('admin')->attempt($request->only(['username', 'password']), true)) {  
			Artisan::call('make:template', ['name' => 'default']);  
			Artisan::call('extension:link', ['extension' => 'template']);
			Artisan::call('extension:link', ['extension' => 'plugin']);
			Artisan::call('extension:link', ['extension' => 'layout']);
			Artisan::call('extension:link', ['extension' => 'module']);

			File::put(__DIR__.'/../../install', time());
			
			Artisan::call('optimize');

			return redirect()->route('admin.login');
		}

		return back()->withErrors(['Login Failed ...! check your credentials.']);
	}

	public function checkDatabaseConnection($request)
	{  
		$this->putEnv($request); 

		Artisan::call('config:clear'); 

		// requested driver
		$driver = $request->input('DB_CONNECTION', 'mysql');

		$config = "database.connections.{$driver}";

		Config::set("database.default", $driver); 
		Config::set("{$config}.charset", 'utf8'); 
		Config::set("{$config}.collation", 'utf8_general_ci'); 
		Config::set("{$config}.host",     $request->input('DB_HOST', '127.0.0.1'));
		Config::set("{$config}.post",     $request->input('DB_PORT', 3306));
		Config::set("{$config}.database", $request->input('DB_DATABASE'));
		Config::set("{$config}.username", $request->input('DB_USERNAME'));
		Config::set("{$config}.password", $request->input('DB_PASSWORD'));
		Config::set("{$config}.prefix",   $request->input('DB_PREFIX'));   

		try { 
			DB::purge($driver);   
			DB::reconnect()->getPdo();   
			return true;
		} catch (Exception $e) { 
			$this->errors[] = $e->getMessage();

			return false;
		}  
	}


	public function makePrefix()
	{ 
		while(preg_match('/^[0-9].*$/', $prefix = $this->randomString()));

		return $prefix. '_';
	}

	protected function randomString()
	{
		return substr(assoc_key(), 0, rand(2, 5));
	}

	public function putEnv($request)
	{ 
		$env = File::get(app()->environmentFilePath()); 

		if(empty($request->input('DB_PREFIX'))) {
			$request = $request->merge(['DB_PREFIX' => $this->makePrefix()]);
		}

		foreach ($this->getEnvirements($request) as $key => $value) {
			$env = preg_replace("/{$key}=[^\s]*/", "{$key}={$value}", $env);
		} 

		File::put(app()->environmentFilePath(), $env);
	}

	public function getEnvirements($request)
	{
		$env = $request->all();

		$env['APP_URL'] = UrlHelper::ensureProtocol($request->getHost());
		$env['APP_DEBUG'] = "false";

		return $env;
	}

	public function mergeErrors($errors = [])
	{
		return array_merge(
			(array) $this->errors, (array) $errors
		);
	}
		 
// end of class
}