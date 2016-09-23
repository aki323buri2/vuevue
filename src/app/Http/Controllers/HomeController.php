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
		Route::post('/home/dirty', __CLASS__.'@dirty');
		Route::post('/home/save', __CLASS__.'@save');
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
	public function dirty(Request $request)
	{

		$catalog = $this->catalog;
		$record = $request->input('record', '[]');
		$record = json_decode($record);

		$catalog->find($record->catno);

		foreach ($record as $name => $value)
		{
			$catalog->$name = $value;
		}
		
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// sleep(rand(0, 1));
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

		return json_encode([
			'exists' => $catalog->exists(), 
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

		$catalog->find($catno);



		foreach ($dirty as $name => $value)
		{
			$catalog->$name = $value;
		}

		$exists = $catalog->exists();

		if ($operation === 'insert' && $exists)
		{
			throw 'catno:'.$catno.' exists!!(can\'t insert)';
		}
		else if ($operation === 'update' && !$exists)
		{
			throw 'catno:'.$catno.' not exists!!(can\'t update)';
		}

		$save = $catalog->save();

		return json_encode([
			'save' => $save , 
			'dirty' => $dirty, 
		], JSON_UNESCAPED_UNICODE);
	}
}