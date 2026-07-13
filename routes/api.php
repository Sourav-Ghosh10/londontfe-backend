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
    Route::get('/categories', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'allCategories']);
    Route::get('/venues', [\App\Http\Controllers\Api\V1\VenueApiController::class, 'index']);
    Route::get('/clients', [\App\Http\Controllers\Api\V1\ClientApiController::class, 'index']);
    Route::get('/seo', [\App\Http\Controllers\Api\V1\SeoApiController::class, 'index']);
    Route::get('/category/{slug}', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'show']);
    Route::get('/course/{category_slug}/{course_slug}', [\App\Http\Controllers\Api\V1\CourseApiController::class, 'show']);
    Route::post('/payment/revolut/create-order', [\App\Http\Controllers\Api\V1\PaymentApiController::class, 'createRevolutOrder']);
    
    Route::post('/enquiry/quickenquery', [\App\Http\Controllers\Api\V1\EnquiryApiController::class, 'quickenquery']);
    Route::post('/enquiry/calendar', [\App\Http\Controllers\Api\V1\EnquiryApiController::class, 'calendarForm']);
    Route::post('/enquiry/schedule', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'downloadCourseSchedule']);
    Route::post('/course-schedule/request-document-download', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'requestDocumentDownload']);
    Route::get('/course-schedule/data', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'getCourseScheduleData']);
    Route::get('/course-schedule/verify/{unqid}', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'verifyScheduleRequest']);
    Route::post('/course-schedule/verify-schedule-mail', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'verifySchedulemail']);
    Route::get('/course-schedule/download/{unqid}', [\App\Http\Controllers\Api\V1\CourseScheduleApiController::class, 'downloadFile']);
    Route::post('/get-in-touch/submit', [\App\Http\Controllers\Api\V1\GetInTouchApiController::class, 'submitForm']);
    Route::get('/testimonials', [\App\Http\Controllers\Api\V1\TestimonialApiController::class, 'index']);
    Route::post('/testimonials/haveyoursay', [\App\Http\Controllers\Api\V1\TestimonialApiController::class, 'haveyoursay']);
    
    // Booking endpoints
    Route::post('/booking/before-pay', [\App\Http\Controllers\Api\V1\BookingApiController::class, 'beforePay']);
    Route::post('/booking/create', [\App\Http\Controllers\Api\V1\BookingApiController::class, 'create']);

    // Dynamic Pages endpoint
    Route::get('/pages/{url}', [\App\Http\Controllers\Api\V1\PageApiController::class, 'show'])->where('url', '.*');
    
    // About Us Profiles endpoint
    Route::get('/about-us-profiles', [\App\Http\Controllers\Api\V1\UserApiController::class, 'aboutUsProfiles']);
});

