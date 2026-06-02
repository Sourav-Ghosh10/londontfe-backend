@extends('admin.layout')

@section('content')
@php
    $logTitle = 'After Checkout';
    $logSlug  = 'after-checkout';
    $storageKey = 'londontfe_logs_after_checkout';
@endphp
@include('admin.logs._log_table')
@endsection
