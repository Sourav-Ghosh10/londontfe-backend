@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Before Payment';
    $logSlug  = 'before-payment';
    $storageKey = 'londontfe_logs_before_payment';
@endphp
@include('admin.logs._log_table')
@endsection
