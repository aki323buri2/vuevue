@extends('layouts/master')

@section('main')


<div class="validate">
	

	<div class="operation-panel collapse">
		<div class="group">
			<button type="button" class="btn btn-primary save" >
				<i class="fa fa-floppy-o"></i>
				更新の反映
			</button>
		</div>
	</div>

	<?php $columns = $catalog->getColumns();?>
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
			
			<?php $no = 0;?>
			@foreach ($data as $row)
				<tr>
					<th scope="row">{{ ++$no }}</th>
					
					@foreach ($columns as $column)
						<?php $name = $column->name;?>
						<?php $value = $row->$name;?>
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
{{ $selector }} .validate table tbody > tr > .dirty
{
	font-weight: bold;
}
</style>

<script>
$(function ()
{

	var container = $('{{ $selector }}');
	var table = container.find('table');
	var panel = container.find('.operation-panel');
	
	loadPlugins();

	table.validate();

	function loadPlugins()
	{
		$.fn.validate = function ()
		{
			if (this.get(0).tagName.toLowerCase() === 'table')
			{
				this.find('tbody tr')
					.addClass('validating')
					.validate()
				;
				return this;
			}

			return this.each(function ()
			{
				var tr = $(this);
				var object = tr.find('td[data-name]').tds2object();
				$.ajax({url: '/home/dirty'
					, type: 'post'
					, data: { record: JSON.stringify(object) }
				})
				.done(function (data)
				{
					tr.removeClass('validating');
					tr.trigger('validate:dirty', [JSON.parse(data)]);
				});
				return tr;
			});
		};
		$.fn.tds2object = function ()
		{
			var object = {}
			this.each(function ()
			{
				var td = $(this);
				var name = td.data('name');
				var value = td.data('value');
				object[name] = value;
			});
			return object;
		};

		table.on('validate:dirty', 'tbody tr', function (e, data)
		{
			var tr = $(this);
			var exists = data.exists;
			var dirty = data.dirty;

			if (tr.parent().find('.vaidating').length === 0)
			{
				tr.closest('table').trigger('validate:dirty-check-complete');
			}

			if (exists && dirty.length === 0) return;

			var operation = !exists 
				? {name: 'insert', color: 'danger', icon: 'plus', title: '新規作成'}
				: {name: 'update', color: 'info'  , icon: 'edit', title: '修正登録'}
			;

			tr.data('operation', operation.name);
			tr.addClass('dirty ' + operation.name);

			var tag = $('<span>')
				.addClass('tag tag-pill tag-' + operation.color)
				.append($('<i>').addClass('fa fa-' + operation.icon))
				.prependTo(tr.find('th:first-child+td'))
			;

			tr.find('.process').text(operation.title);

			$.each(dirty, function (name, value)
			{
				var td = tr.find('.' + name);
				td.addClass('dirty table-' + operation.color);
			});

		});
		table.on('validate:dirty-check-complete', function (e)
		{
			panel.collapse('show');
		});
		table.on('click', '.tag', function (e)
		{
			$(this).closest('tr').toggleLocked();
		});
		$.fn.toggleLocked = function ()
		{
			var tr = this;
			return !tr.hasClass('locked') ? tr.addClass('locked') : tr.removeClass('locked');
		};
		panel.on('click', '.save', function (e)
		{
			table.save();
		});
		$.fn.save = function ()
		{
			if (this.length === 0) return this;
			
			if (this.get(0).tagName.toLowerCase() === 'table')
			{
				this.find('tbody tr.dirty:not(.locked)')
					.addClass('saving')
					.save()
				;
				return this;
			}

			return this.each(function ()
			{
				var tr = $(this);
				var catno = tr.find('.catno').data('value');
				var dirty = tr.find('td.dirty').tds2object();
				var operation = tr.data('operation');
				$.ajax({url: '/home/save'
					, type: 'post'
					, data: {operation: operation, catno: catno, dirty: JSON.stringify(dirty)}
				})
				.done(function (data)
				{
					console.log('??');
					// console.log($(data).text());
					tr.trigger('validate:saved', [data]);
				});

				return tr;
			});
		};
		JSON.tryParse = function (text)
		{
			try 
			{
				return JSON.parse(text);
			}
			catch (e)
			{
				return null;
			}
		};
		table.on('validate:saved', 'tbody tr', function (e, data)
		{
			console.log(data);
		});
	};
});
</script>

@endsection