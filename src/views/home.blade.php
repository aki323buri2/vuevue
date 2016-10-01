@extends('layouts/sidebar')
@section('title', 'home')

<?php

// dump(Schema::getColumnListing($catalog->getTable()));

$columns = $catalog->getColumns();

$timestamps = matrix(
				['name', 'type', 'title']
				, 
				[
					['created_at', 'timestamp', '登録日時'], 
					['updated_at', 'timestamp', '修正日時'], 
				]
				, 'name'
			);


?>

@push('styles')
<style>
#table1 th
{
	background: #ccc;
	text-align: center;
}
#table1 tbody td.nouka, 
#table1 tbody td.baika, 
#table1 tbody td.stanka
{
	text-align: right;
	width: 8rem;
}
#table1 tbody td.created_at, 
#table1 tbody td.updated_at 
{
	text-align: center;
	width: 11rem;
}

#table1 tbody .card
{
	cursor: pointer;
}
#table1 tr .tag.tag-pill
{
	position: absolute;
	margin-left: -7rem;
}

#modal1 .iframe1
{
	width: 100%;
	height: 700px;
}
</style>
@endpush

@section('main')

<p>home</p>

<!-- Large modal -->
<div
	id="modal1" 
	class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="gridModalLabel">Modal title</h4>
			</div>
			
			<div class="modal-body">
				<div class="modal-content">
					<iframe src="" frameborder="0" class="iframe1"></iframe>
				</div>
			</div>
		
		</div>
	</div>
</div>



<table class="table table-sm table-bordered table-hover" id="table1">
	<thead>
		<tr>
			<th>#</th>
			@foreach ($columns->merge($timestamps) as $name => $column)
			<?php $name = $column->name;?>
			<?php $title = $column->title;?>

			<th
				class="{{ $name }}"
				data-name="{{ $name }}"
				data-title="{{ $title }}"
			>
				{{ $title }}
			</th>
			@endforeach
		</tr>
	</thead>
	
	<tbody></tbody>

</table>

@endsection

@push('scripts-after')
<script>
$(function ()
{
	var table = $('#table1');

	loadPlugins();

	$.ajax({url: '/home/list'
	})
	.done(function (data)
	{
		table.display(JSON.parse(data));
	});

	table.on('click', '.card', function (e)
	{
		var tr = $(this).closest('tr');
		tr.showCard();		
	});
});
function loadPlugins()
{
	$.fn.display = function (data)
	{
		var table = this;
		var thead = table.find('thead');
		var tbody = table.find('tbody').empty();

		var no = 0;

		var names = thead.find('[data-name]').map(function ()
		{
			return $(this).data('name');
		});

		$.each(data, function (index, row)
		{
			var tr = $('<tr>').appendTo(tbody).data({catno: row.catno});
			var th = $('<th>').appendTo(tr).addClass('no').text(++no);

			names.each(function ()
			{
				var name = this.toString();
				var value = row[name];

				var td = $('<td>').appendTo(tr)
					.data({name: name, value: value})
					.addClass(name).text(value)
				;
			});

			// tag
			var tag = $('<span>')
				.addClass('tag tag-pill tag-info card')
				.appendTo(tr.find('.catno'))
				.append($('<i>').addClass('fa fa-edit'))
			;

			// tooltip on tag
			tag
				.data({toggle: 'tooltip', placement:'top'})
				.prop({title: row.hinmei+' ('+row.catno+') の編集'})
				.tooltip()
			;
			 
		});

		return this;
		
	};
	$.fn.showCard = function ()
	{
		var tr = this;
		var catno = tr.data('catno');

		var href = '/home/card/'+catno;

		var modal = $('#modal1');
		var iframe = modal.find('.iframe1');

		iframe.prop('src', href);


		modal
			.offon('shown.bs.modal', shown)
			.offon('hidden.bs.modal', hidden)
		;

		tr.addClass('table-danger');
		modal.modal('show');
		
		function shown(e)
		{
			iframe.focus();
		}
		function hidden(e)
		{
			iframe.removeProp('src');
			tr.removeClass('table-danger');
		}


		return this;
	};
	$.fn.offon = function (type, callback)
	{
		return this.off(type).on(type, callback);
	};
};
</script>
@endpush