@extends('layouts.dashboard')

@section('title')
Favorites
@endsection

@section('content')

@php
//favorites
$j_filter = '{
    ":uid": "'.$user_sub.'",
    ":true": true
}';
$filter_expression = 'UserID = :uid  and Favorite = :true';
@endphp

@include('inc.list_filter')

@endsection
