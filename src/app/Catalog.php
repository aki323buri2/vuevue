<?php
namespace App;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;

class Catalog extends Model
{
	protected $columns;

	protected $table = 'catalog';

	public function __construct()
	{
		parent::__construct();

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

		$this->primaryKey = $columns->first()->name;
	}
	public function getColumns()
	{
		return $this->columns;
	}
}
