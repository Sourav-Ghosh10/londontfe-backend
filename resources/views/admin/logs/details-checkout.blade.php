@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Details Checkout';
    $logSlug  = 'details-checkout';
    $storageKey = 'londontfe_logs_details_checkout';
@endphp
@include('admin.logs._log_table')
@endsection
