<?php
namespace App\Http\Controllers;

use App;
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
		Route::post('/home/validate', __CLASS__.'@validate');
		Route::get('/home/confirm', __CLASS__.'@confirm');
	}

	protected $catalog;

	public function __construct()
	{
		App::useDatabase();
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
		$load = $this->catalog->get();
		return View::make('paste', [
				'catalog' => $this->catalog, 
			]);
	}
	public function validate(Request $request)
	{
		$this->catalog->get();

		$selector = $request->input('selector');

		$data = $request->input('data', '[]');
		$data = json_decode($data);
		
		return View::make('validate', [
				'catalog' => $this->catalog, 
				'selector' => $selector, 
				'data' => $data, 
			]);
	}
	public function confirm(Request $request)
	{

		$catalog = $this->catalog;
		$catno = $request->input('catno');

		$catalog->find($catno);

		return $catalog->all()->toJson();
	}
}