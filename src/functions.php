<?php
function dump($value)
{
	echo "<pre>\n";
	var_dump($value);
	echo "</pre>\n";
}
function extname($path)
{
	return preg_match('/[.][^.]+$/', $path, $match) ? $match[0] : '';
}
function extname_without_dot($path)
{
	return preg_replace('/^[.]/', '', extname($path));
}
function matrix($names, $values)
{
	$names = collect($names);
	$combine = collect($values)->map(function ($values, $index) use ($names)
	{
		return $names->combine(array_pad($values, count($names), null));
	});
	return $combine;
}
