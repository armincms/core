<?php 
namespace Core\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{ 

	/**
     * Show your admin panel.
     * 
     * @return void
     */
	public function index()
	{ 
		return view('dashboard::index');
	}

	/**
     * Show your admin panel.
     * 
     * @return void
     */
	public function show()
	{ 
		return view();
	}
}