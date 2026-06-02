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

Route::get('/admin/courses/popular', function () {
    return view('admin.courses.popular');
});

Route::get('/admin/courses/categories', function () {
    return view('admin.courses.categories');
});

Route::get('/admin/courses/categories/create', function () {
    return view('admin.courses.categories_create');
});

Route::get('/admin/courses/venues', function () {
    return view('admin.courses.venues');
});

Route::get('/admin/courses/venues/create', function () {
    return view('admin.courses.venues_create');
});

Route::get('/admin/courses/venues/view', function () {
    return view('admin.courses.venues_view');
});

Route::get('/admin/courses/currencies', function () {
    return view('admin.courses.currencies');
});

Route::get('/admin/courses/promocodes', function () {
    return view('admin.courses.promocodes');
});

// Users Routes
Route::get('/admin/users', function () {
    return view('admin.users.index');
});

Route::get('/admin/users/create', function () {
    return view('admin.users.create');
});

// Website Routes
Route::get('/admin/website/clients', function () {
    return view('admin.website.clients.index');
});

Route::get('/admin/website/clients/create', function () {
    return view('admin.website.clients.create');
});

Route::get('/admin/website/banners', function () {
    return view('admin.website.banners.index');
});

Route::get('/admin/website/banners/create', function () {
    return view('admin.website.banners.create');
});

Route::get('/admin/website/pages', function () {
    return view('admin.website.pages.index');
});

Route::get('/admin/website/pages/create', function () {
    return view('admin.website.pages.create');
});

Route::get('/admin/website/accreditation', function () {
    return view('admin.website.accreditation.index');
});

Route::get('/admin/website/accreditation/create', function () {
    return view('admin.website.accreditation.create');
});

Route::get('/admin/website/autoreply', function () {
    return view('admin.website.autoreply.index');
});

Route::get('/admin/website/autoreply/{id}/edit', function ($id) {
    return view('admin.website.autoreply.edit');
});

Route::get('/admin/website/testimonials', function () {
    return view('admin.website.testimonials.index');
});

Route::get('/admin/website/testimonials/create', function () {
    return view('admin.website.testimonials.create');
});

Route::get('/admin/website/testimonials/{id}/edit', function ($id) {
    return view('admin.website.testimonials.edit');
});

Route::get('/admin/website/gallery', function () {
    return view('admin.website.gallery.index');
});

Route::get('/admin/website/gallery/create', function () {
    return view('admin.website.gallery.create');
});

// Blog Routes
Route::get('/admin/blog', function () {
    return view('admin.blog.index');
});

Route::get('/admin/blog/create', function () {
    return view('admin.blog.create');
});

Route::get('/admin/blog/categories', function () {
    return view('admin.blog.categories');
});

Route::get('/admin/blog/categories/create', function () {
    return view('admin.blog.categories_create');
});

// Course Price Routes
Route::get('/admin/course-price/tiers', function () {
    return view('admin.course-price.tiers');
});

Route::get('/admin/course-price/location-bands', function () {
    return view('admin.course-price.location-bands');
});

Route::get('/admin/course-price/location-bands/create', function () {
    return view('admin.course-price.location-bands_create');
});

// Log Routes
Route::get('/admin/logs/quick-enquiry', function () {
    return view('admin.logs.quick-enquiry');
});

Route::get('/admin/logs/download-outline', function () {
    return view('admin.logs.download-outline');
});

Route::get('/admin/logs/details-checkout', function () {
    return view('admin.logs.details-checkout');
});

Route::get('/admin/logs/cart', function () {
    return view('admin.logs.cart');
});

Route::get('/admin/logs/before-payment', function () {
    return view('admin.logs.before-payment');
});

Route::get('/admin/logs/after-checkout', function () {
    return view('admin.logs.after-checkout');
});

Route::get('/admin/logs/coupon', function () {
    return view('admin.logs.coupon');
});
