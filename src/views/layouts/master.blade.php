<?php
$links = array_merge([

	'/vendor/jquery/dist/jquery.js',
	'/vendor/jquery-ui/themes/base/jquery-ui.css', 
	'/vendor/jquery-ui/jquery-ui.js',
	'/vendor/font-awesome/css/font-awesome.css',
	'/vendor/tether/dist/js/tether.js',
	'/vendor/bootstrap/dist/js/bootstrap.js',

	// '/vendor/bootstrap/dist/css/bootstrap.css',
	'/build/bootstrap/dist/css/bootstrap-custom.css',

], (array)@$links
);
$links = collect($links)->groupBy(function ($url)
{
	return extname_without_dot($url);
})->toArray();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title')</title>
@foreach ((array)@$links['css'] as $url)
	<link rel="stylesheet" href="{{ $url }}">
@endforeach 
@foreach ((array)@$links['js'] as $url)
	<script src="{{ $url }}"></script>
@endforeach 
@stack('styles')
@stack('scripts')
</head>
<body>

<div id="topbar">
	@yield('topbar')
</div>
<div id="sidebar">
	@yield('sidebar')
</div>
<div id="main">
	@yield('main')
</div>

@stack('scripts-after')

</body>
</html>