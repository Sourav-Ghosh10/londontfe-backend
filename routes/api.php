<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BannerApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/banners', [BannerApiController::class, 'index']);
    Route::get('/popular-courses', [\App\Http\Controllers\Api\V1\CourseApiController::class, 'popularCourses']);
    Route::get('/course-filters', [\App\Http\Controllers\Api\V1\CourseApiController::class, 'filters']);
    Route::get('/courses', [\App\Http\Controllers\Api\V1\CourseApiController::class, 'index']);
    Route::get('/featured-categories', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'featuredCategories']);
    Route::get('/clients', [\App\Http\Controllers\Api\V1\ClientApiController::class, 'index']);
    Route::get('/seo', [\App\Http\Controllers\Api\V1\SeoApiController::class, 'index']);
    Route::get('/category/{slug}', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'show']);
});
