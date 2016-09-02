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
	<li class="list-group-item">
		<span class="tag tag-default tag-pill pull-xs-right">14</span>
		Cras justo odio
	</li>
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