<?php

namespace Armin\Core; 
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator; 
use Illuminate\Http\Request;
use Armin\Http\Controllers\FilesController; 
use Exception; 
use Session;  
use Auth;
use Image; 
use HTMLMin; 
 
use Carbon\carbon;     
use Armin\Models\Setting;    
use Core\User\Models\Admin;   
use Armin\Exceptions\AccessDeniedException;

class HelperClass extends Controller
{
  
    protected $upload_path = 'files';
    protected $thumbnail = '-thumbnail';
    protected $main = '-main';

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
     * Is loggined user superadministrator or not.
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
     * Make dropdown from array of items .
     * 
     * @param  array   $parents  
     * @param  Clusre  $get_child   [need callabel function for find child(s) of item]
     * @param  Clusre  $drawer  [need callabel function for draw an item and its child(s)]
     * @param  integer $depth   [depth of parent]
     * @return string          
     */
    public function dropdown($parents, $get_child, $drawer, $depth=0)
    {     
        $dropdowned = ''; 

        foreach ($parents as $parent) {   

            if (! is_callable($get_child)) 
                return "Missing Callbak Function For Fetch Childs Of Menu [id: {$parent->id}] In Depth {$depth}.";

            if (! is_callable($drawer)) 
                return "Missing Callbak Function For Draw Menu [id: {$parent->id}] In Depth {$depth}."; 

            // find childs of  mneu
            $childs = call_user_func_array($get_child, [$parent]);

            // print created childs
            ob_start(); 
                echo call_user_func_array([$this, 'dropdown'], [$childs, $get_child, $drawer, $depth+1]);
            $printed = ob_get_clean();
            // recall
            $dropdowned .= call_user_func_array($drawer, [$parent, $printed, $depth, $childs]);  
        }   

        return $dropdowned;
    } 
      

    /**
     * [contentUrl retrun absolute url for content]
     * @param  encoded url
     * @return abolute url  
     */
    public function contentUrl($path=null)
    {
        $base = request()->route()->getPrefix();
        $path = urldecode($path);
        
        $validator = Validator::make(['url' => $path], ['url' => 'active_url']);

        if (! $validator->fails()) {
            // its a full url
            return $path;
        }

        if (preg_match('/#/', $path)) {
            return '#!';
        }
        
        // thats not a full url
        return is_null($path) ? url($base) : url(trim($base. '/'.urldecode(trim($path, '/')), '/'));
    }
    /**
     * Compare two url without domain.
     * 
     * @param  string $url1 
     * @param  string $url2 
     * @return boolean       
     */
    public function compareUrl($url1, $url2)
    { 
        global $locale;

        $url1 = preg_replace("/" .\App::getLocale(). "\/?/", '', $url1);
        $url2 = preg_replace("/" .\App::getLocale(). "\/?/", '', $url2); 

        $url1 = parse_url($url1);
        $url2 = parse_url($url2);  

        $url1 = isset($url1['path']) ? trim(urldecode($url1['path']), '/') : '';
        $url2 = isset($url2['path']) ? trim(urldecode($url2['path']), '/') : ''; 
        
        return $url1 == $url2; 
    }

    /*
*
*   get sorted languages for insert related languages 
************************************************************************************/

    function SortedLanguages()
    {     
        $key = 1;

        return language()->sortBy(function ($language) use (&$key) { 
            return $language->alias == \App::getLocale()? 0 : $key++;
        }); 
    }
   
/*
*
*   group Contents by languages
************************************************************************************/
    
    public function GroupByLanguage($contents)
    {
        $locale = language(\App::getLocale()); 
        $grouped = [];   

        while ($content = array_shift($contents)) {
            if ($content->language_id == $locale->id) {
                $grouped[$content->depend_id][0] = $content; 
            }else{
                if (!isset($grouped[$content->depend_id][0])) {
                    $grouped[$content->depend_id][0] = null;
                } 
                $grouped[$content->depend_id][] = $content; 
            }
            
        }     
        return $grouped;
    }

    function CreateImages($request, $file, $path){  
        $file_controller = new FilesController();  
        $files = $file_controller->FindUploader($request, $file, $path); 

        $uploaded = []; 
        $file_id = '';        
          
        foreach ($files['files'] as $file_id => $file_path) {  
            if (is_null($file)) { 
                $uploaded[$file_id] = $this->Asset($file_path); 
            } 

            // create Thumbnail image 
            $Thumbnail = Image::make($file_path);   
            $width = $Thumbnail->height() > 300 ? 480/$Thumbnail->height() : 1; 
            $width = $width * $Thumbnail->width();  
            $Thumbnail->resize($width, 300);  

            $Thumbnail->crop(480, 300);  
            $Thumbnail->save($this->Thumbnail($file_path));  
            // create resized image ot arbitrary size

            $resize = Image::make($file_path);  
            $resize->fit(810, 320); 
            $resize->save($this->MainImage($file_path)); 
        } 

        if (is_null($file)) { 
            return $uploaded; 
        } 

        return is_array($file_id) ? array_shift($file_id) : $file_id;

    } 

    /*
*
*   get sorted languages for insert related languages 
************************************************************************************/
	
	function Uploader(Request $request){
		$file = new FilesController();
		return $file->uploader($request);
	}


/*
*   find modifier of content
*
****************************************/

    function Modifier(){    
        // fro find rule of user that create this content
        // if admins login we added a- before thats id
        // if users  login we added u- before thats id 
        if (Auth::guard('admin')->check()) {
            return 'a-' . Auth::guard('admin')->id(); 
        } else { 
            return 'u-' . Auth::guard('user')->id(); 
        } 
    }
  

    function activeTemplate()
    {   
        return active_template();
    } 
 
 
/*
*
*   return absolute url for uploaded contents 
************************************************************************************/

    function FilePath($path, $type = null){  
        if ($type) {
            $path = str_replace('.', '-'.$type.'.', $path);
        }
        $path = $this->UploadPath().$path; 
        return $this->Asset($path); 
    }

    /*
*
*   path of uploaded files
************************************************************************************/

    function UploadPath($path=NULL){   
        $path = preg_replace('/(\\\\|\/){1,}/', DS, $path);
        return $this->upload_path.DS.$path; 
    }

 
/*
*
*   return folder of contents uploaded
************************************************************************************/

    function Asset($path=NULL){  
        return $this->IsSecure() ? secure_asset($path) : asset($path); 
    }

/*
*
*   return true if connection is secure
************************************************************************************/
    
    public function IsSecure($value='')
    {
        return false;
    }

/*
*
*   get path of file and return Thumbnail path of image
************************************************************************************/
    
    public function Thumbnail($path)
    {
        return str_replace('.', $this->thumbnail .'.', $path);
    }


/*
*
*   get path of file and return Thumbnail path of image
************************************************************************************/
    
    public function MainImage($path)
    {
        return str_replace('.', $this->main .'.', $path);
    }

/*
*
*   return wanted words that recived
************************************************************************************/
    
    public function WordCount($string, $length = 50, $more = '  ...')
    {  
        $words = \Illuminate\Support\Str::words($string, $length, $more);

        $noraml_length = $length*10; 
        
        return strlen($words) > $noraml_length ? mb_substr($string, 0, $noraml_length, "utf-8") : $words;
    }

    public function icons($type = null)
    {
        $icons_file = __DIR__.DS.'icons.php';

        if (! file_exists($icons_file)) return [];

        $icons = require 'icons.php';

        if(! isset($type)) return $icons; 

        return isset($icons[$type]) ? $icons[$type] : [];
    }

/*
*
*   make slug name for url
************************************************************************************/
    function Slug($str, $options = array())
    {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        // Options
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => false,
            'replacements' => array(),
            'transliterate' => false,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
            // Persian
            'ة' => 'ه', 'ۀ' => 'ه', 'ؤ' => 'و', 'ي' => 'ی', 'ك' => 'ک', 'ء' => '', 'أ' => 'ا', 'إ' => 'ا',
            "٤" => "۴", "٥" => "۵", "٦" => "۶", 'ـ' => '_',
        );

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }  
  
}// end of helper
