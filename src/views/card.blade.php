@extends('layouts/master')
<?php
$links = array_merge((array)@$links, [
	'/vendor/vue/dist/vue.js',
]);

$columns = $catalog::getColumns();

?>

@push('styles')
<style>
#container1
{
	width: 600px;
	margin-top: 2rem;
}
</style>
@endpush

@section('main')

<div class="container" id="container1">


<div class="card card-block">
	<h4 class="card-title">
		{{ $catno }}
		<span v-text="hinmei"></span>
	</h4>
	<p class="card-text">
		Some quick example text to build on the card title and make up the bulk of the card's content.
	</p>

<form id="form1">
	
	@foreach ($columns as $column)
	<div class="form-group">
		<label for="{{ $column->name }}">
			{{ $column->title }}
		</label>
		<input 
			type="text"
			class="form-control"
			id="{{ $column->name }}"
			placeholder="{{ $column->title }}"

			v-model="{{ $column->name }}"
		>
	</div>
	@endforeach
	
</form>

	<a href="#" class="card-link">Card link</a>
	<a href="#" class="card-link">Another link</a>
</div>

<script>
$(function ()
{
	var data = {};
	@foreach ($data as $name => $value)
	data['{{ $name }}'] = '{{ $value }}';
	@endforeach

	new Vue({
		el: '#container1'
		, data: data 
	});
});
</script>

@endsection