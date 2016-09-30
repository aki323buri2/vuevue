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
	cursor: pointer;
}
</style>
@endpush

@section('main')

<div class="container" id="container1">


<div class="card card-block">
	<h4 class="card-title">
		{{ $catno }}
		@{{ values.hinmei }}
		@{{ values.sanchi }}
		@{{ values.tenyou }}
		@{{ values.mekame }}
	</h4>
	<p class="card-text">
		@{{ check.exists }}
		Some quick example text to build on the card title and make up the bulk of the card's content.
	</p>
	<p>
		<div class="created_at">@{{ values.created_at }} 登録</div>
		<div class="updated_at">@{{ values.updated_at }} 修正</div>
	</p>

<form id="form1">
	
	<div class="form-group row" v-for="title in titles">
		<label for="@{{ $key }}" class="col-sm-2">
			@{{ title }}
		</label>
		<div class="col-sm-10">
			<input 
				type="text"
				class="form-control"
				id="@{{ $key }}"
				placeholder="@{{ title }}"

				v-model="values[$key]"
			>
		</div>
			
	</div>
	
</form>

	<a href="#" class="card-link save">Save</a>
	<a href="#" class="card-link cancel">Cancel</a>
</div>

<script>
$(function ()
{
	initPlugins();

	var container = $('#container1');
	
	container.vueThis();

	container.on('click', '.save', function (e)
	{
		e.preventDefault();
		container.check();
	});


});
function initPlugins()
{
	$.fn.vueThis = function ()
	{
		var data = {};
		data.titles = {};
		data.values = {};
		data.check = {};
		@foreach ($columns as $name => $column)
		data.titles['{{ $name }}'] = '{{ $column->title }}';
		@endforeach

		@foreach ($data as $name => $value)
		data.values['{{ $name }}'] = '{{ $value }}';
		@endforeach

		data.check.exists = '';
		data.check.dirty = [];

		var vue = new Vue({
			el: this[0]
			, data: data 
		});

		data.check.exists = '....';

		this.prop('vue', vue);

		return this;
	};
	$.fn.check = function ()
	{
		var container = this;
		var vue = container.prop('vue');
		var record = JSON.stringify(vue.values);

		$.ajax({
			url: '/home/dirty'
			, type: 'post'
			, data: { record: record }
		})
		.done(function (data)
		{
			var check = JSON.parse(data);
			var exists = check.exists;
			var dirty = check.dirty;

			vue.check.exists = (exists ? '修正' : '新規');
		});
	};
};
</script>

@endsection