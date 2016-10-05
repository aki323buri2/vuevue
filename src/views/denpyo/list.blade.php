@extends('layouts/sidebar')
@section('title', '配送伝票')

<?php
$links = array_merge((array)@$links, [
	'/vendor/dropzone/dist/min/dropzone.min.css',
	'/vendor/dropzone/dist/min/dropzone.min.js',
]);
?>

@push('styles')
<style>
#search+i.fa.fa-search
{
	font-size: 1.2rem;
	color: #ccc;
	position: absolute;
	margin-top: -1.8rem;
	margin-left: 1rem;
}
#search
{
	padding-left: 2.5rem;
	width: 130px;
	transition: width .4s ease-in-out;
	--webkit-transition: width .4s ease-in-out;

	border-radius: 1.2rem;
}
#search:focus
{
	width: 300px;
}



</style>
@endpush

@section('main')
<p>
	<i class="fa fa-truck"></i>
	配送伝票
</p>


<div class="" id="dropzone1"></div>

<script>
$(function ()
{
	var $dz = $('#dropzone1').addClass('dropzone').dropzone({
		url: '/denpyo/csv/upload'
		, accept: function (file, done)
		{
			if (file.name.split('.').pop() === 'csv')
			{
				done();
			}
			else 
			{
				var error = 'not csv!';
				alert(error);
				done(error);
				this.removeFile(file);
			}
		}
		, addRemoveLinks: true
	});
	var  dz = $dz[0].dropzone;
	dz.on('addedfile', function (file)
	{
		var type = file.type;
		var ext = file.name.split('.').pop();
		var img = $(file.previewElement)
			.find('.dz-image img')
			.prop('src', '/img/csv512.png')
			.prop('width', '120')
		;
	})
	.on('success', function (file, response)
	{
		console.log(response);
	})
	;
});
</script>

@endsection