<?php
namespace App;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;

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

		$this->primaryKey = $columns->first()->name;
	}
	public function getColumns()
	{
		return $this->columns;
	}

	public function get($columns = ['*'])
	{
		$get = $this->loadFile($this->sourceFile);

		return $get;
	}
	public function save(array $options = [])
	{
		return $this->saveFile($this->sourceFile);
	}

	public function loadFile($file)
	{
		$load = @file_get_contents($file);
		$load = (array)@json_decode($load, true);

		$entries = collect();
		
		foreach ($load as $load)
		{
			$entry = new static();
		
			foreach ($load as $name => $value)
			{
				$entry->setAttribute($name, $value);
			}
			
			$entries->push($entry);
		}

		return $entries;
	}
	public function saveFile($file)
	{
		$load = $this->loadFile($file);

		$key = $this->getKey();

		$append = true;

		foreach ($load as &$object)
		{
			if ($object->getKey() === $key)
			{
				$object = $this;
				$append = false;
				break;
			}
		}

		if ($append) $load->push($this);


		$json = $load->map(function ($object, $index) 
		{
			return $object->toJson(JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		});

		$json = "[\n".$json->implode("\n,\n")."\n]";

		file_put_contents($file, $json);
	}

	public function find($id, $columns = ['*'])
	{
		if (is_array($id))
		{
			return $this->findMany($id, $columns);
		}

		$load = $this->get();

		$find = $load->filter(function ($object, $index) use ($id)
		{
			return $object->getKey() === $id;
		});

		return $find->first();
	}
	public function findMany($ids, $columns = ['*'])
	{
		if (empty($ids))
		{
			return null;
		}

		$load = $this->get();

		$find = $load->filter(function ($object, $index) use ($ids)
		{
			return in_array($object->getKey(), $ids, true/*type check*/);
		});

		return $find;
	}

}