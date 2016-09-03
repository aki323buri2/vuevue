<?php
namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
	protected $columns;

	protected $sourceFile;

	public function __construct()
	{
		$this->sourceFile = App::basePath().'/storage/catalog.json';

		$columns = matrix(
			['name', 'type', 'title']
			, 
			[
				['catno'  , 'string' , 'カタログＣＤ'], 
				['shcds'  , 'string' , 'ｼｮｸﾘｭｰＣＤ'], 
				['eoscd'  , 'string' , 'ＥＯＳＣＤ'], 
				['makeme' , 'string' , 'メーカー名'], 
				['shiren' , 'string' , '仕入先ＣＤ'], 
				['hinmei' , 'string' , '品名'], 
				['sanchi' , 'string' , '産地'], 
				['tenyou' , 'string' , '天・養'], 
				['nouka'  , 'float'  , '納価'], 
				['baika'  , 'float'  , '売価'], 
				['stanka' , 'float'  , '仕入'], 
			]
			, 'name'
		);
		$this->columns = $columns;

		$names = $columns->pluck('name');

		foreach ($names as $name)
		{
			$this->setAttribute($name, null);

			$this->casts[$name] = $this->columns->get($name)->type;
		}

	}
	public function getColumns()
	{
		return $this->columns;
	}
	
	public function get($columns = ['*'])
	{
		$results = $this->loadFile($this->sourceFile);

		return $results;
	}
	public function loadFile($file)
	{
		$load = @file_get_contents($file);
		$load = (array)@json_decode($load);

		//

		return $load;
	}
}