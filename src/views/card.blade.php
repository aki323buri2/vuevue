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
#form1 .form-group.row label
{
	/*overflow: hidden;*/
	white-space: nowrap;
}
</style>
@endpush

@section('main')

<div class="container" id="container1">


<div class="card card-block">
	<h4 class="card-title">
		{{ $catno }}
		<span v-text="values.hinmei"></span>
		<span v-text="values.sanchi"></span>
		<span v-text="values.tenyou"></span>
		<span v-text="values.mekame"></span>
	</h4>
	<p class="card-text">
		Some quick example text to build on the card title and make up the bulk of the card's content.
	</p>
	<p>
		<div class="created_at">@{{ values.created_at }} 登録</div>
		<div class="updated_at">@{{ values.updated_at }} 修正</div>
	</p>

<form id="form1">
	
	@foreach ($columns as $column)
	<div class="form-group row">
		<label for="{{ $column->name }}" class="col-sm-2">
			{{ $column->title }}
		</label>
		<div class="col-sm-10">
			<input 
				type="text"
				class="form-control"
				id="{{ $column->name }}"
				placeholder="{{ $column->title }}"

				v-model="values.{{ $column->name }}"
			>
		</div>
			
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
	data.titles = {};
	data.values = {};
	@foreach ($data as $name => $value)
	data.titles['{{ $name }}'] = '{{ @$columns[$name]->title }}';
	data.values['{{ $name }}'] = '{{ $value }}';
	@endforeach

	new Vue({
		el: '#container1'
		, data: data 
	});
});
</script>

@endsection