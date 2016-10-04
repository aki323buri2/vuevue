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

<div action="/file-upload" class="dropzone">
  <div class="fallback">
    <input name="file" type="file" multiple />
  </div>
</div>

<script>
$(function ()
{
	
});
</script>

@endsection