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
		[@{{ values.catno }}]
		@{{ values.hinmei }}
		@{{ values.sanchi }}
		@{{ values.tenyou }}
		@{{ values.mekame }}
	</h4>
	
	<div class="operation" v-show="operation !== 'none'" transition="collapse">
		<div class="tag tag-@{{ bsClass }}">
			@{{ existsText }}
		</div>

		<span
			v-show="dirtyCount > 0"
		>
			<div 
				class="tag tag-@{{ dirtyCount ? 'danger' : 'default' }}"
			>
				@{{ dirtyCount }}項目
			</div>
		</span>
	</div>

	<p>
		<div class="created_at">@{{ values.created_at }} 登録</div>
		<div class="updated_at">@{{ values.updated_at }} 修正</div>
	</p>

	<form id="form1">
		
		<div class="form-group row @{{ $key }}" v-for="title in titles">
			<label for="@{{ $key }}" class="col-sm-2">
				@{{ title }}
			</label>
			<div class="col-sm-10">
				<input 
					type="text"
					class="form-control @{{ $key }}"
					id="@{{ $key }}"
					placeholder="@{{ title }}"

					v-model="values[$key]"

					v-on:keyup="inputOnChange"
					v-on:change="inputOnChange"
				>
			</div>
				
		</div>
		
	</form>

</div>

<script>
$(function ()
{
	initPlugins();

	var container = $('#container1');
	
	container.vueThis();

	container.check();

	container.find('.form-control.shcds').select();

});
/**
 * init plugins 
 * @return {jquery object} 
 */
function initPlugins()
{
	$.fn.vueThis = function ()
	{
		// register trasition 
		transition();

		// vue compile
		var vue = new Vue({
			el: this[0]
			, data: vueData() 
			, computed: vueComputed()
			, methods: vueMethods()
		});
		this.prop('vue', vue);


		return this;
	};

	/**
	 * data for vue
	 * @return {object}
	 */
	function vueData()
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

		data.check.exists = false;
		data.check.dirty = {};

		return data;
	}

	/**
	 * computed for vue
	 * @return {object}
	 */
	function vueComputed()
	{
		return {
			  dirtyCount: function ()
			{
				return Object.keys(this.check.dirty).length;
			}
			, operation: function ()
			{
				if (this.dirtyCount > 0)
				{
					return !this.check.exists ? 'insert' : 'update';
				}
				else
				{
					return 'none';
				}
			}
			, bsClass: function ()
			{
				switch (this.operation)
				{
					case 'insert' : return 'danger';
					case 'update' : return 'info';
					case 'none' : return 'default';
				}
			}
			, existsText : function ()
			{
				switch (this.operation)
				{
					case 'insert' : return '新規';
					case 'update' : return '修正';
					case 'none' : return '変更なし';
				}
			}
		};
	}

	/**
	 * methods for vue
	 * @return {object}
	 */
	function vueMethods()
	{
		return {
			inputOnChange: function (e)
			{
				$(this.$el).check();
			}
		};
	};

	/**
	 * check exists and dirty by ajax
	 * @return jquery {object}
	 */
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

			vue.check.exists = exists;
			vue.check.dirty = dirty;

			container.find('.form-group.row').removeClass('has-danger');

			$.each(check.dirty, function (name, value)
			{
				container.find('.form-group.row.'+name).addClass('has-danger');
			});
		});

		return this;
	};

	/**
	 * register transition
	 * @return hook
	 */
	function transition()
	{
		Vue.transition('collapse', {
			css: false
			, enter: function (el, done)
			{
				$(el).collapse('show');
			}
			, enterCancelled: function (el)
			{
				$(el).stop();
			}
			, leave: function (el, done)
			{
				$(el).collapse('hide');
			}
			, leaveCancelled: function (el)
			{
				$(el).stop();
			}
		});
	}
};
</script>

@endsection