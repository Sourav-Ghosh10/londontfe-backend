@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Quick Enquiry Event';
    $logSlug  = 'quick-enquiry';
    $storageKey = 'londontfe_logs_quick_enquiry';
@endphp
@include('admin.logs._log_table')
@endsection
