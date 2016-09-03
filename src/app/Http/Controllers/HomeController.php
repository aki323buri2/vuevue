<?php
namespace App\Http\Controllers;

use Route;
use View;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Catalog;

class HomeController extends Controller 
{
	public static function routes()
	{
		Route::get('/', __CLASS__.'@index');
		Route::get('/home', __CLASS__.'@index');
		Route::get('/home/paste', __CLASS__.'@paste');
	}

	protected $catalog;

	public function __construct()
	{
		$this->catalog = new Catalog;
	}

	public function index(Request $request)
	{
		return View::make('home', [
				'catalog' => $this->catalog, 
			]);
	}
	public function paste(Request $request)
	{
		$this->catalog->get();
		
		return View::make('paste', [
				'catalog' => $this->catalog, 
			]);
	}
}