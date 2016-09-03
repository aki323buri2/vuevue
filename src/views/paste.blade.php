@extends('layouts/sidebar')
@section('title', 'コピー＆ペースト')
<?php
$links = array_merge((array)@$links, [
'/vendor/handsontable/dist/handsontable.full.js',
'/vendor/handsontable/dist/handsontable.full.css',
]);

$columns = $catalog->getColumns();
?>

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

	var data = this.getSourceData();

	console.log([state, data]);
}
</script>
@endpush