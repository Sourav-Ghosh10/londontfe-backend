@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Cart';
    $logSlug  = 'cart';
    $storageKey = 'londontfe_logs_cart';
@endphp
@include('admin.logs._log_table')
@endsection
