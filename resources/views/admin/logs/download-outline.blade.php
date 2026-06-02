@extends('admin.layout')

@section('content')
@php
    $logTitle = 'Download Full Outline';
    $logSlug  = 'download-outline';
    $storageKey = 'londontfe_logs_download_outline';
@endphp
@include('admin.logs._log_table')
@endsection
