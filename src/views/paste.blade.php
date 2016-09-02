@extends('layouts/sidebar')
@section('title', 'コピー＆ペースト')
<?php
$links = array_merge((array)@$links, [
'/vendor/handsontable/dist/handsontable.full.js',
'/vendor/handsontable/dist/handsontable.full.css',
]);
?>


@section('main')

<p>コピー＆ペースト</p>

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
	});

	return hot.handsontable('getInstance');
}
</script>
@endpush