@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Coupon';
    $logSlug  = 'coupon';
    $storageKey = 'londontfe_logs_coupon';
@endphp
@include('admin.logs._log_table')
@endsection
