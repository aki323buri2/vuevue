@extends('layouts/topbar')
@push('styles')
<style>
#sidebar
{
	float: left;
	width: 20rem;
}
#main
{
	margin-left: 21rem;
}
</style>
@endpush
@section('sidebar')

<ul class="list-group">
	<a href="/home" class="list-group-item list-group-item-action">
		
		<i class="fa fa-home"></i>
		Home

		<span class="tag tag-danger tag-pill pull-xs-right">14</span>
	</a>
	<a href="/home/paste" class="list-group-item list-group-item-action">
		
		<i class="fa fa-copy"></i>
		コピー＆ペースト

		<span class="tag tag-info tag-pill pull-xs-right">5</span>
	</a>
	<li class="list-group-item">
		<span class="tag tag-default tag-pill pull-xs-right">2</span>
		Dapibus ac facilisis in
	</li>
	<li class="list-group-item">
		<span class="tag tag-default tag-pill pull-xs-right">1</span>
		Morbi leo risus
	</li>
</ul>

@endsection