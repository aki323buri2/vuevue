<?php
namespace App\Application;

use Illuminate\Container\Container;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Facade;

use Route;

class Application extends Container 
{
	/**
	 * application base path
	 * 
	 * @var string
	 */
	protected $basePath; 

	/**
	 * facade map
	 * 
	 * @var array
	 */
	protected $facades;
	
	/**
	 * constructor
	 * 
	 * @param string $basePath
	 * @return  void
	 */
	public function __construct($basePath)
	{
		// set instance pointer
		static::setInstance($this);
		$this->instance('app', $this);
		$this->instance('container', $this);

		// set basePath
		$this->basePath = $basePath;

		// env
		$this->instance('env', '');

		// configration
		$this->instance('config', new Fluent);

		// facade autoload
		Facade::setFacadeApplication($this);

		spl_autoload_register(function ($alias)
		{
			$facades = static::getInstance()->facades;
			$abstract = @$facades[$alias];
			if (isset($abstract)) return class_alias($abstract, $alias);
		}, true, true);

		// register basic providers
		$this->registerProviders(
			  \Illuminate\Events\EventServiceProvider::class
			, \Illuminate\Routing\RoutingServiceProvider::class
			, \Illuminate\Filesystem\FilesystemServiceProvider::class
			, \Illuminate\View\ViewServiceProvider::class
		);

		// register basic facades..
		$this->addFacades([
			'App' => \Illuminate\Support\Facades\App::class, 
			'Route' => \Illuminate\Support\Facades\Route::class, 
			'View' => \Illuminate\Support\Facades\View::class, 
			'Config' => \Illuminate\Support\Facades\Config::class, 
			'Request' => \Illuminate\Support\Facades\Request::class, 
			'Response' => \Illuminate\Support\Facades\Response::class, 
			'Input' => \Illuminate\Support\Facades\Input::class, 
		]);

		// add basic alisases
		$this->addAliases([
			'request' => [\Illuminate\Http\Request::class], 
		]);

		// add view config
		$viewConfig = [
			'view' => [
				'paths' => [$this->basePath.'/src/views'], 
				'compiled' => $this->basePath.'/storage/view/compiled', 
			], 
		];
		$this->addConfig($viewConfig);
	}

	/**
	 * add config
	 * 
	 * @param  array $config
	 * @return static
	 */
	public function addConfig($config)
	{
		foreach ($config as $prefix => $config)
		{
			foreach ($config as $name => $value)
			{
				$this['config'][$prefix.'.'.$name] = $value;
			}
		}

		return $this;
	}

	/**
	 * register providers
	 * 
	 * @param  array $providers
	 * @return static
	 */
	public function registerProviders($providers)
	{
		if (!is_array($providers)) $providers = func_get_args();

		foreach ($providers as $provider)
		{
			(new $provider($this))->register();
		}

		return $this;
	}


	/**
	 * add Facades hash map
	 * 
	 * @param  array $facades
	 * @return  static
	 */
	public function addFacades($facades)
	{

		$this->facades = array_merge((array)$this->facades, $facades);

		return $this;
	}
	
	/**
	 * add Aliases
	 * 
	 * @param  array $aliases
	 * @return static 
	 */
	public function addAliases($aliases)
	{
		foreach ($aliases as $alias => $abstracts)
		{
			foreach ((array)$abstracts as $abstract)
			{
				$this->alias($alias, $abstract);
			}
		}

		return $this;
	}

	/**
	 * dispatch request & response
	 * 
	 * @return void
	 */
	public function dispatch()
	{
		$this->instance('request' , $request  = \Illuminate\Http\Request::capture());
		$this->instance('response', $response = Route::dispatch($request));
		$response->send();
	}

	/**
	 * environment
	 * 
	 * @return string
	 */
	public function environment()
	{
		return $this['env'];
	}

	/**
	 * base path
	 * 
	 * @return string
	 */
	public function basePath()
	{
		return $this->basePath;
	}

	/**
	 * database path
	 * 
	 * @return string
	 */
	public function databasePath()
	{
		return $this->basePath().'/src/database';
	}

}