<?php

return [
	
	'fetch' => PDO::FETCH_CLASS, 

	'default' => 'sqlite', 

	'connections' => [

		'sqlite' => [
			'driver' => 'sqlite', 
			'database' => App::databasePath().'/database.sqlite', 
			'prefix' => '', 
		], 

		'mysql' => [
			'driver' => 'mysql', 
			'host' => 'localhost', 
			'port' => '3306', 
			'database' => basename(App::basePath()), 
			'username' => 'shokuryu', 
			'password' => 'shokuryu', 
			'charset' => 'utf8', 
			'collation' => 'utf8_unicode_ci', 
			'prfix' => '', 
			'strict' => false, 
			'engine' => null, 
		],

	],  

	'migrations' => 'migrations', 

	'redis' => [

		'cluster' => false, 

		'default' => [
			'host' => 'localhost', 
			'password' => 'shokuryu', 
			'port' => 6379, 
			'database' => 0, 
		], 

	], 

];