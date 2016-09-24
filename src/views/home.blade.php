@extends('layouts/sidebar')
@section('title', 'home')

<?php

dump($catalog->getColumns());
?>
@section('main')

<p>home</p>



@endsection