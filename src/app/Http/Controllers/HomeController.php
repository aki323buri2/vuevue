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

		$methods = [
			'list', 
			'paste', 
			'validate', 
			'dirty', 
			'save', 
		];
		foreach ($methods as $method)
		{
			Route::match(['get', 'post'], '/home/'.$method, __CLASS__.'@'.$method);
		}

		Route::match(['get', 'post'], '/home/card/{catno?}', __CLASS__.'@card');
	}

	protected $catalog;

	public function __construct()
	{
		App::useDatabase();
		$this->catalog = new Catalog;
	}

	public function index(Request $request)
	{
		$catalog = $this->catalog;

		return View::make('home', [
				'catalog' => $catalog, 
			]);
	}
	public function list(Request $request)
	{
		$list = $this->catalog->get();

		return $list->toJson(JSON_UNESCAPED_UNICODE);
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
	public function card(Request $request, $catno)
	{
		dump($catno);
		exit;

		return View::make('card', [
			'catalog' => $this->catalog, 
			'catno' => $catno, 
		]);
	}

	public function dirty(Request $request)
	{

		$catalog = $this->catalog;
		$record = $request->input('record', '[]');
		$record = json_decode($record);

		$catalog = $catalog->find($record->catno);
		$exists = !is_null($catalog);
		if (!$exists)
		{
			$catalog = new Catalog;
		}

		foreach ($record as $name => $value)
		{
			$catalog->$name = $value;
		}
		
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// sleep(rand(0, 1));
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

		return json_encode([
			'exists' => $exists,  
			'dirty' => $catalog->getDirty(), 
		], JSON_UNESCAPED_UNICODE);
	}
	public function save(Request $request)
	{
		$catalog = $this->catalog;
		$operation = $request->input('operation');
		$catno = $request->input('catno');
		$dirty = $request->input('dirty', '[]');
		$dirty = json_decode($dirty);

		$catalog = $catalog->find($catno);
		$exists = !is_null($catalog);

		if ($operation === 'insert' && $exists)
		{
			throw 'catno:'.$catno.' exists!!(can\'t insert)';
		}
		else if ($operation === 'update' && !$exists)
		{
			throw 'catno:'.$catno.' not exists!!(can\'t update)';
		}

		if (!$exists)
		{
			$catalog = new Catalog;
			$catalog->catno = $catno;
		}

		foreach ($dirty as $name => $value)
		{
			$catalog->$name = $value;
		}



		$save = $catalog->save();

		return json_encode([
			'save' => $save , 
			'dirty' => $dirty, 
		], JSON_UNESCAPED_UNICODE);
	}
}