<?php
namespace App\Http\Controllers;

use Route;
use View;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller 
{
	public static function routes()
	{
		Route::get('/', __CLASS__.'@index');
		Route::get('/home', __CLASS__.'@index');
	}

	public function __construct()
	{
	}

	public function index(Request $request)
	{
		return View::make('home');
	}
}