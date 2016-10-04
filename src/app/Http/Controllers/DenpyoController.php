<?php
namespace App\Http\Controllers;

use App;
use Route;
use View;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class DenpyoController extends Controller 
{
	public static function routes()
	{
		Route::get('/denpyo', __CLASS__.'@index');

		$methods = [
			'list'
		];
		foreach ($methods as $method)
		{
			Route::match(['get', 'post'], '/denpyo/'.$method, __CLASS__.'@'.$method);
		}
	}
	public function __call($method, $args)
	{
		return View::make($method, []);
	}

	public function __construct()
	{
		App::useDatabase();
	}
	public function index(Request $request)
	{
		return View::make('denpyo.list', []);
	}
}