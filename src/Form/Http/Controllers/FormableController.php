<?php
namespace Core\Form\Http\Controllers;

use App\Http\Controllers\Controller;
use Core\Content\Models\Content;
use Illuminate\Database\Eloquent\Model;  
use Core\Form\Form;
use Form as Builder;

class FormableController extends Controller
{
	function index()
	{ 
		$form = new Form();
		// event(new CreatingForm($form));
		$form->element('text', 'title');
		// event(new CreatedForm($form));
		

		// event(new StoringForm($form))
		// $form->store();
		// event(new StoredForm($form))
		

		// event(new UpdatingForm($form))
		// $form->update();
		// event(new UpdatedForm($form))

		return view('form::edit')->withForm($form);
	}

}