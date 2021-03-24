
@php
//Trash
$j_filter = '{
    ":uid": "26ac4c01-c47d-4125-afc5-5747cb99d7d8",
    ":true": true
}';
$filter_expression = 'UserID = :uid  and Deleted = :true';
@endphp

@include('inc.list_filter')
