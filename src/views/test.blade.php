@extends('layouts/master')
@section('title', 'test')
<?php
$links = array_merge((array)@$links, [
	'/vendor/vue/dist/vue.js',
]);
?>
@section('main')

<div id="app">
	@{{ checked ? 'checked' : 'unchecked' }}
</div>

<button type="button" class="btn btn-default" id="button1">button</button>

<script>
$(function ()
{
	var data = {};
	data.checked = false;
	var vm = new Vue({
		el: '#app'
		, data: data
	});
	vm.checked = true;

	$(document).on('click', '#button1', function (e)
	{
		data.checked = !data.checked;
	});
});
</script>
@endsection