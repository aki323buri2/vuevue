@extends('layouts/master')

@section('main')

<?php $columns = $catalog->getColumns()?>
<table class="table table-sm table-bordered">
	<thead>
		<tr>
			<th>#</th>
			@foreach ($columns as $column)
				<th
					class="{{ $column->name }}"
					data-name="{{ $column->name }}"
					data-title="{{ $column->title }}"
				>
					{{ $column->title }}
				</th>
			@endforeach
			<th class="process">
				状況
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $no = 0?>
		@foreach ($data as $row)
			<tr>
				<th scope="row">{{ ++$no }}</th>
				@foreach ($columns as $column)
					<?php $name = $column->name?>
					<?php $value = @$row->$name?>
					<td
						class="{{ $name }}"
						data-name="{{ $name }}"
						data-value="{{ $value }}"
					>
						{{ $value }}
					</td>
				@endforeach 
				<td class="process">
					
				</td>
			</tr>
		@endforeach
		
	</tbody>
</table>

<?php if (!$selector) $selector = 'body'?>
<style>
{{ $selector }} table:first-child .process
{
	width: 20rem;
}
{{ $selector }} table:first-child tbody > tr > * > .tag
{
	position: absolute;
	margin-left: -3rem;
}
{{ $selector }} table:first-child tbody > tr > .dirty
{
	font-weight: bold;
	background: #f1c40f;
}
</style>

<script>
$(function ()
{
	extendJQuery();

	var selector = '{{ $selector }}';
	var container = $(selector);
	
	var table = container.find('table');
	var tbody = table.find('tbody');
	tbody.find('tr').each(function ()
	{
		getDirty($(this));
	});

	function getDirty(tr)
	{
		var record = JSON.stringify(recordFromTr(tr));

		tr.processShow('・・・確認しています・・・');

		$.ajax({
			url: '/home/dirty'
			, data: { record: record }
		})
		.done(function (data)
		{
			var object = JSON.parse(data);
			tr.applyObject(object);
		});
	};
	function recordFromTr(tr)
	{
		record = {};

		tr.find('td[data-value]').each(function ()
		{
			var td = $(this);
			var name = td.data('name');
			var value = td.data('value');
			record[name] = value;
		});

		return record;
	}
	//================================================
	//================================================
	//================================================
	function extendJQuery()
	{
		$.fn.processShow = function (text)
		{
			var tr = this;
			
			var td = tr.find('.process');
			
			td.text(text);

			return this;
		};
		$.fn.applyObject = function (object)
		{
			var tr = this;
			
			var exists = object.exists;
			var dirty  = object.dirty;
			
			if (!exists)
			{
				tr.addClass('insert');
			}
			else if (dirty.length())
			{
				tr.addClass('update');
			}

			$.each(dirty, function (name, value)
			{
				var td = tr.find('.' + name);
				td.addClass('dirty');
			});

			var type = 
				  (tr.hasClass('insert') ? {color: 'danger' , icon: 'plus', text: '新規登録'}
				: (tr.hasClass('update') ? {color: 'primary', icon: 'edit', text: '登録の修正'}
				: ''))
				;
			tr.processShow(type.text);

			var th = tr.find('th:first-child');

			var tag = $('<span>')
				.appendTo(th)
				.addClass('tag tag-pill tag-' + type.color)
				.append($('<i>').addClass('fa fa-' + type.icon))
			;

			return this;
		};

	};
});
</script>

@endsection