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
function matrix($names, $values, $keyBy = null)
{
	$names = collect($names);
	$combine = collect($values)->map(function ($values, $index) use ($names)
	{
		return (object)$names->combine(array_pad($values, count($names), null))->toArray();
	});
	if (!is_null($keyBy))
	{
		$combine = $combine->keyBy($keyBy);
	}
	return $combine;
}
