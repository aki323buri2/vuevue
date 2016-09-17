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

<script>
$(function ()
{
	var selector = '{{ $selector ?: 'body' }}';
	var container = $(selector);
	var table = container.find('table');
	var tbody = table.find('tbody');
	table.find('tr').each(function ()
	{
		validate($(this));
	});

	function validate(tr)
	{
		var catno = tr.find('.catno').data('value');
		$.ajax({
			url: '/home/confirm'
			, data: { catno: catno }
		})
		.done(function (data)
		{
			console.log(data);
		});
	};
});
</script>

@endsection