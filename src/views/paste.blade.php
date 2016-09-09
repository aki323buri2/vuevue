@extends('layouts/sidebar')
@section('title', 'コピー＆ペースト')
<?php
$links = array_merge((array)@$links, [
'/vendor/handsontable/dist/handsontable.full.js',
'/vendor/handsontable/dist/handsontable.full.css',
]);

$columns = $catalog->getColumns();

$cache = @file_get_contents(App::basePath().'/storage/cache.json');
$cache = (array)@json_decode($cache);


$app = App::getInstance();
$config = Config::getInstance();

$database = [
	'fetch' => PDO::FETCH_CLASS, 
	'default' => 'sqlite', 
	'connections' => [
		'sqlite' => [
			'driver' => 'sqlite', 
			'database' => $app->basePath().'/storage/database.sqlite', 
			'prefix' => '',
		], 
	], 
	'migrations' => 'migrations', 
];
$prefix = 'database';
foreach ($database as $key => $value)
{
	$config[$prefix.'.'.$key] = $value;
}
$providers = [
	Illuminate\Database\DatabaseServiceProvider::class, 
];
foreach ($providers as $provider)
{
	(new $provider($app))->register();
}
$facades = [
	'DB' => Illuminate\Support\Facades\DB::class, 
	'Eloquent' => Illuminate\Database\Eloquent\Model::class, 
];
$app->facades = array_merge((array)$app->facades, $facades);

// dump(DB::table('catalog')->get());

?>

@push('styles')
<style>
table tr > th
{
	text-align: center;
	background: #ccc;
}
</style>
@endpush

@section('main')

<p>コピー＆ペースト</p>

<div id="validate"></div>

<div id="table1"></div>

@endsection

@push('scripts-after')
<script>
$(function ()
{
	var hot = handson($('#table1'));

	$(hot.rootElement).on('catalog.validate', catalogValidate);

	var data = JSON.parse('{!! json_encode($cache, JSON_UNESCAPED_UNICODE) !!}');
	hot.loadData(data);
	hot.setDataAtCell(0, 0, hot.getDataAtCell(0, 0));

});
function handson(el)
{
	var hot = el.handsontable({
		columns: columns()
		, rowHeaders: true
		, data: [[]]
		, afterChange: handsonAfterChange
	});

	return hot.handsontable('getInstance');
}
function columns()
{
	var columns = [];

	@foreach ($columns as $column)
		
		(function ()
		{
			var column = {};
			column.data = '{{ $column->name }}';
			column.title = '{{ $column->title }}';
			columns.push(column);
		})();

	@endforeach 

	return columns;
}
function handsonAfterChange(changes, state)
{
	if (state === 'loadData') return;

	var hot = this;
	var data = hot.getData();

	var objects = [];

	$.each(data, function (row, values)
	{
		var object = {};
		$.each(values, function (col, value)
		{
			var prop = hot.colToProp(col);
			object[prop] = value;
		});
		objects.push(object);
	});

	$(hot.rootElement).trigger('catalog.validate', [objects, state]);
}
function catalogValidate(e, data, state)
{
	$.ajax({url: '/home/validate'
		, type: 'post'
		, data: { data: JSON.stringify(data) }
	})
	.done(function (data)
	{
		var validate = $(data).find('table:first-child');
		$('#validate').empty().append(validate);
	});
}
</script>
@endpush