@extends('layouts.dashboard')

@section('content')

@php
//Deleted
$j_filter = '{
    ":uid": "'.$user_sub.'",
    ":deleted": true
}';
$filter_expression = 'UserID = :uid  and Deleted = :deleted';
@endphp

@include('inc.list_filter')

@endsection

