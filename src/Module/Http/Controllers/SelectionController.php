<?php 
namespace Core\Module\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use Core\Module\ModuleInstance as Module;
use Core\Module\Forms\SelectionForm;

class SelectionController extends Controller
{     
    public function handle(Request $request, $module = null)
    {         
        return view('module::selection', compact('module'));
    } 
}
