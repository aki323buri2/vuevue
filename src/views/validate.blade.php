@extends('layouts/master')

@section('main')

<?php $columns = $catalog->getColumns()?>

<div class="validate">
	
	<div class="operation-panel collapse">
		<div class="group">
			<button type="button" class="btn btn-primary save" >
				<i class="fa fa-floppy-o"></i>
				更新の反映
			</button>
		</div>
	</div>

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

</div>

<?php if (!$selector) $selector = 'body'?>
<style>
{{ $selector }} .validate .operation-panel
{
	border: 1px solid #ccc;
	border-radius: .3rem;
	padding: 1rem;
	margin-bottom: .5rem;
	/*display: none;*/
}
{{ $selector }} .validate table .process
{
	width: 20rem;
}
{{ $selector }} .validate table tbody > tr > * > .tag
{
	position: absolute;
	margin-left: -4rem;
	cursor: pointer;
}
{{ $selector }} .validate table tbody > tr.locked
{
	opacity: .5;
}
{{ $selector }} .validate table tbody > * > .dirty
{
	font-weight: bold;
	background: #f1c40f;
}
</style>

<script>
$(function ()
{
	//================================================
	extendJQuery();
	//================================================

	var selector = '{{ $selector }}';
	var container = $(selector);

	var panel = container.find('.operation-panel');

	var save = panel.find('button.save');
	
	var table = container.find('table');
	var tbody = table.find('tbody');

	tbody.find('tr').each(function ()
	{
		getDirty($(this));
	});

	save.on('click', saveClick);

	function getDirty(tr)
	{
		var record = JSON.stringify(recordFromTr(tr));

		tr.processShow('・・・確認しています・・・');

		table.initProcess();

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
	};
	function saveClick(e)
	{
		table.save();
	};
	//================================================
	//================================================
	//================================================
	function extendJQuery()
	{
		$.fn.initProcess = function ()
		{
			this.find('tbody > tr').addClass('doing');

			return this;
		};
		$.fn.doingProcess = function ()
		{
			return this.siblings('.doing');
		};
		$.fn.markAsDoneProccess = function ()
		{
			this.removeClass('doing');

			if (this.doingProcess().length === 0)
			{
				panel.collapse('show');
			}

			return this;
		};
		$.fn.processShow = function (text)
		{
			return this.find('.process').text(text);
		};
		$.fn.applyObject = function (object)
		{
			var tr = this;
			
			var exists = object.exists;
			var dirty	= object.dirty;
			
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

			var operation = 
				  (tr.hasClass('insert') ? {color: 'danger' , icon: 'plus', text: '新規登録'}
				: (tr.hasClass('update') ? {color: 'primary', icon: 'edit', text: '登録の修正'}
				: ''))
				;
			tr.processShow(operation.text);

			var target = tr.find('th:first-child+td');

			var tag = tagPill(operation.color)
				.append(faIcon(operation.icon))
				.prependTo(target)
			;

			tag.data({
					toggle: 'tooltip'
				, placement: 'right'
				, title: operation.text
			}).tooltip();

			tag.on('click', tagClick);

			tr.markAsDoneProccess();


			return this;
		};

		var faIcon = function (icon)
		{
			return $('<i>').addClass('fa fa-' + icon);
		};
		var tagPill = function (color)
		{
			return $('<span>').addClass('tag tag-pill tag-' + color);
		};
		var tagClick = function (e)
		{
			var tr = $(this).closest('tr');

			tr.lockThis(!tr.locked());
		};
		$.fn.lockThis = function (lock)
		{
			var tr = $(this);
			return lock ? tr.addClass('locked') : tr.removeClass('locked');
		};
		$.fn.locked = function ()
		{
			return $(this).hasClass('locked');
		};

		$.fn.save = function ()
		{
			var todo = this.getTodo();

			$.each(todo, function (index, todo)
			{
				var catno = todo.catno;
				var dirty = todo.dirty;


			});

			return this;
		};
		$.fn.getTodo = function ()
		{
			var tr = $(this);
			var todo = [];
			tr.find('tbody > tr:not(.locked)').each(function ()
			{
				var dirty = $(this).dirtyToObject();
				if (dirty === null) return;

				todo.push(dirty);
			});

			return todo;
		};
		$.fn.dirtyToObject = function ()
		{
			var tr = this;
			var catno = tr.find('.catno').data('value');
			var object = {};

			tr.find('.dirty').each(function ()
			{
				var dirty = $(this).data();
				object[dirty.name] = dirty.value;
			});

			if (Object.keys(object) === 0) return null;

			return {catno: catno, dirty: object};
		};
	};
});
</script>

@endsection