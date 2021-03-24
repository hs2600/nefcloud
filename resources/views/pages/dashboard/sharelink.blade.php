@extends('layouts.dashboard')

@section('content')

@php
//Shared
$j_filter = '{
    ":uid": "'.$user_sub.'",
    ":true": true
}';
$filter_expression = 'UserID = :uid  and #shared = :true';
@endphp

@include('inc.list_filter')

@endsection

