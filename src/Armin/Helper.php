<?php 
namespace Core\Armin;

use Illuminate\Foundation\AliasLoader; 
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Helper  
{

    /**
     * Add aliases to the loader.
     *
     * @param  string|array  $alias
     * @param  string|null  $class
     * @return void
     */
	public function registerAlias($alias, $class = null)
	{ 
		static::facade($alias, $class); 
	}

    /**
     * Register facade to loader.
     *
     * @param  string|array  $alias
     * @param  string|null  $class
     * @return void
     */
    public function facade($alias, $class = null)
    { 
        $aliases = is_array($alias) ? $alias : [$alias => $class];

        $loader = AliasLoader::getInstance();

        foreach ($aliases as $alias => $class) {
            $loader->alias($alias, $class);
        } 
    }

    /**
     * Registers a set of PSR-4 directories for a given namespace.
     *
     * @param  array $paths
     * @return void
     */
    public function resolvePsr4(array $paths)
    {   
        $loader = new ClassLoader;

        foreach ($paths as $namespace => $path) {  
            if(! ends_with($namespace, '\\')) {
                $namespace .= '\\';
            }
            
            $loader->addPsr4($namespace, $path); 
        } 

        $loader->register(); 
    }

    /**
     * Fromat receiving datetime.
     *
     * @param  mixed $datetime
     * @param  string $format
     * @return string 
     */
    public function format($datetime = null, string $format = null)
    {
        $carbon = Carbon::make($datetime ?: now());

        return is_null($format) ? "{$carbon}" : $carbon->format($format);
    }

    /**
     * Is current requst path of panel.
     * 
     * @return boolean 
     */
    public function isPanelPath()
    {
        return request()->is('panel') || request()->is('panel/*');
    }

    /**
     * Get path of panel.
     * 
     * @return boolean 
     */
    public function panelPath($path = null)
    {
        return trim(config('panel.path', 'panel'), '/') . ($path ? '' : "/{$path}");
    }

    /**
     * Is loggined user superadministrator or not.
     * 
     * @return boolean 
     */
    public function isSuperAdmin()
    {
        if($user = Auth::guard('admin')->user()) {
            return $user->hasRole('superadministrator');
        }

        return false; 
    } 

    /**
     * Is loggined user dministrator or not.
     * 
     * @return boolean 
     */
    public function isAdministrator()
    {
        if($user = Auth::guard('admin')->user()) {
            return $user->hasRole('administrator');
        }

        return false;  
    }

    /**
     * Is loggined user is admin or not.
     * 
     * @return boolean 
     */
    public function isAdmin()
    {   
        return Auth::guard('admin')->check();   
    }

    /**
     * Validate user permisision.
     * 
     * @param  string $permission 
     * @return void             
     */
    public function checkPermission($permission, $team = null, $requireAll = false)
    { 
        return true;
        
        if (! request()->user()->can($permission, $team = null, $requireAll = false))  {  
            throw new AccessDeniedException("Sorry. Your Access Is Denied. Call To Your Administrator.");
        } 
    }

    /**
     * Validate user permisision and ownable.
     * 
     * @param  string $permission 
     * @param  object $thing 
     * @return void             
     */
    public function canAndOwns($permission, $thing, $options = [])
    {
        if (! request()->user()->canAndOwns($permission, $thing, $options))  {  
            throw new AccessDeniedException("Sorry. Your Access Is Denied. Call To Your Administrator.");
        } 
    }  

    /**
     * Get world's of sentence with length limitation.
     * 
     * @param  string   $sentence 
     * @param  integer  $words 
     * @return string   $more             
     * @return integer  $length             
     */
    public function readmore(string $sentence, int $words=50, int $length=null, string $more='...')
    {   
        $string = Str::words($sentence, $words, '.'); 

        return ! is_null($length) && $words > 1 && mb_strlen($string) >= $length 
                    ? $this->readmore($sentence, --$words, $length, $more) 
                    : Str::words($sentence, $words, $more); 
    }

}