<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.login');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/courses', function () {
    return view('admin.courses.index');
});

Route::get('/admin/courses/create', function () {
    return view('admin.courses.create');
});
