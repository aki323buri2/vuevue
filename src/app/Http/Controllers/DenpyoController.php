<?php
namespace App\Http\Controllers;

use App;
use Route;
use View;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;

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

		Route::post('/denpyo/csv/upload', __CLASS__.'@csvUpload');
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

	public function csvUpload(Request $request)
	{
		$file = $request->file('file');
		$path = $file->getRealPath();
		
		$config = new LexerConfig;
		$config 
			->setToCharSet('UTF-8')
			->setFromCharset('SJIS-win')
		;

		$interpreter = new Interpreter;
		$interpreter->unstrict();

		$lexer = new Lexer($config);

		$csv = [];
		$interpreter->addObserver(function (array $row) use (&$csv)
		{
			$csv[] = $row;
		});
		$lexer->parse($path, $interpreter);

		dump($csv);
	}
}