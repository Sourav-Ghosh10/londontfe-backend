<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;

Route::get('/', function () {
    return view('admin.login');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('admin.courses.index');
Route::get('/admin/courses/create', [\App\Http\Controllers\Admin\CourseController::class, 'create'])->name('admin.courses.create');
Route::post('/admin/courses', [\App\Http\Controllers\Admin\CourseController::class, 'store'])->name('admin.courses.store');
Route::get('/admin/courses/{id}/edit', [\App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('admin.courses.edit');
Route::put('/admin/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('admin.courses.update');
Route::delete('/admin/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('admin.courses.destroy');

Route::get('/admin/courses/popular', [\App\Http\Controllers\Admin\CourseController::class, 'popular'])->name('admin.courses.popular');
Route::post('/admin/courses/popular', [\App\Http\Controllers\Admin\CourseController::class, 'updatePopular'])->name('admin.courses.popular.update');
Route::patch('/admin/courses/{id}/toggle-popular', [\App\Http\Controllers\Admin\CourseController::class, 'togglePopular']);

use App\Http\Controllers\Admin\CourseCategoryController;

Route::get('/admin/courses/categories', [CourseCategoryController::class, 'index']);
Route::get('/admin/courses/categories/create', [CourseCategoryController::class, 'create']);
Route::post('/admin/courses/categories', [CourseCategoryController::class, 'store']);
Route::get('/admin/courses/categories/{id}/edit', [CourseCategoryController::class, 'edit']);
Route::put('/admin/courses/categories/{id}', [CourseCategoryController::class, 'update']);
Route::delete('/admin/courses/categories/{id}', [CourseCategoryController::class, 'destroy']);
Route::patch('/admin/courses/categories/{id}/toggle-featured', [CourseCategoryController::class, 'toggleFeatured']);

use App\Http\Controllers\Admin\VenueController;

Route::get('/admin/courses/venues', [VenueController::class, 'index']);
Route::get('/admin/courses/venues/create', [VenueController::class, 'create']);
Route::post('/admin/courses/venues', [VenueController::class, 'store']);
Route::get('/admin/courses/venues/{id}', [VenueController::class, 'show'])->name('admin.courses.venues.show');
Route::get('/admin/courses/venues/{id}/edit', [VenueController::class, 'edit'])->name('admin.courses.venues.edit');
Route::put('/admin/courses/venues/{id}', [VenueController::class, 'update'])->name('admin.courses.venues.update');
Route::delete('/admin/courses/venues/{id}', [VenueController::class, 'destroy'])->name('admin.courses.venues.destroy');

use App\Http\Controllers\Admin\CurrencyController;

Route::get('/admin/courses/currencies', [CurrencyController::class, 'index'])->name('admin.currencies.index');
Route::post('/admin/courses/currencies', [CurrencyController::class, 'update'])->name('admin.currencies.update');

Route::get('/admin/courses/promocodes', function () {
    return view('admin.courses.promocodes');
});

Route::get('/admin/courses/send-outline', [\App\Http\Controllers\Admin\SendOutlineController::class, 'index'])->name('admin.courses.send-outline');
Route::post('/admin/courses/send-outline', [\App\Http\Controllers\Admin\SendOutlineController::class, 'send'])->name('admin.courses.send-outline.send');
Route::get('/admin/courses/send-outline/dates', [\App\Http\Controllers\Admin\SendOutlineController::class, 'getDates'])->name('admin.courses.send-outline.dates');

// Users Routes
use App\Http\Controllers\Admin\UserController;

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

// Website Routes
use App\Http\Controllers\Admin\ClientController;

Route::get('/admin/website/clients', [ClientController::class, 'index'])->name('admin.website.clients.index');
Route::get('/admin/website/clients/create', [ClientController::class, 'create'])->name('admin.website.clients.create');
Route::post('/admin/website/clients', [ClientController::class, 'store'])->name('admin.website.clients.store');
Route::get('/admin/website/clients/{id}/edit', [ClientController::class, 'edit'])->name('admin.website.clients.edit');
Route::post('/admin/website/clients/{id}', [ClientController::class, 'update'])->name('admin.website.clients.update');
Route::patch('/admin/website/clients/{id}/toggle-status', [ClientController::class, 'toggleStatus'])->name('admin.website.clients.toggle-status');
Route::delete('/admin/website/clients/{id}', [ClientController::class, 'destroy'])->name('admin.website.clients.destroy');

use App\Http\Controllers\Admin\BannerSliderController;

Route::get('/admin/website/banners', [BannerSliderController::class, 'index'])->name('admin.website.banners.index');
Route::get('/admin/website/banners/create', [BannerSliderController::class, 'create'])->name('admin.website.banners.create');
Route::post('/admin/website/banners', [BannerSliderController::class, 'store'])->name('admin.website.banners.store');
Route::get('/admin/website/banners/{id}/edit', [BannerSliderController::class, 'edit'])->name('admin.website.banners.edit');
Route::post('/admin/website/banners/{id}', [BannerSliderController::class, 'update'])->name('admin.website.banners.update');
Route::patch('/admin/website/banners/{id}/toggle-status', [BannerSliderController::class, 'toggleStatus'])->name('admin.website.banners.toggle-status');
Route::delete('/admin/website/banners/{id}', [BannerSliderController::class, 'destroy'])->name('admin.website.banners.destroy');

use App\Http\Controllers\Admin\PageContentController;

Route::get('/admin/website/pages', [PageContentController::class, 'index'])->name('admin.website.pages.index');
Route::get('/admin/website/pages/create', [PageContentController::class, 'create'])->name('admin.website.pages.create');
Route::post('/admin/website/pages', [PageContentController::class, 'store'])->name('admin.website.pages.store');
Route::get('/admin/website/pages/{id}/edit', [PageContentController::class, 'edit'])->name('admin.website.pages.edit');
Route::post('/admin/website/pages/{id}', [PageContentController::class, 'update'])->name('admin.website.pages.update');
Route::patch('/admin/website/pages/{id}/toggle-status', [PageContentController::class, 'toggleStatus'])->name('admin.website.pages.toggle-status');
Route::delete('/admin/website/pages/{id}', [PageContentController::class, 'destroy'])->name('admin.website.pages.destroy');

use App\Http\Controllers\Admin\AccreditationController;

Route::get('/admin/website/accreditation', [AccreditationController::class, 'index'])->name('admin.website.accreditation.index');
Route::get('/admin/website/accreditation/create', [AccreditationController::class, 'create'])->name('admin.website.accreditation.create');
Route::post('/admin/website/accreditation', [AccreditationController::class, 'store'])->name('admin.website.accreditation.store');
Route::get('/admin/website/accreditation/{id}/edit', [AccreditationController::class, 'edit'])->name('admin.website.accreditation.edit');
Route::post('/admin/website/accreditation/{id}', [AccreditationController::class, 'update'])->name('admin.website.accreditation.update');
Route::patch('/admin/website/accreditation/{id}/toggle-status', [AccreditationController::class, 'toggleStatus'])->name('admin.website.accreditation.toggle-status');
Route::delete('/admin/website/accreditation/{id}', [AccreditationController::class, 'destroy'])->name('admin.website.accreditation.destroy');


use App\Http\Controllers\Admin\AutoReplyController;
use App\Http\Controllers\Admin\TestimonialController;

Route::get('/admin/website/autoreply', [AutoReplyController::class, 'index'])->name('admin.website.autoreply.index');
Route::get('/admin/website/autoreply/{id}/edit', [AutoReplyController::class, 'edit'])->name('admin.website.autoreply.edit');
Route::post('/admin/website/autoreply/{id}', [AutoReplyController::class, 'update'])->name('admin.website.autoreply.update');

Route::get('/admin/website/testimonials', [TestimonialController::class, 'index'])->name('admin.website.testimonials.index');
Route::get('/admin/website/testimonials/create', [TestimonialController::class, 'create'])->name('admin.website.testimonials.create');
Route::post('/admin/website/testimonials', [TestimonialController::class, 'store'])->name('admin.website.testimonials.store');
Route::get('/admin/website/testimonials/{id}/edit', [TestimonialController::class, 'edit'])->name('admin.website.testimonials.edit');
Route::post('/admin/website/testimonials/{id}', [TestimonialController::class, 'update'])->name('admin.website.testimonials.update');
Route::post('/admin/website/testimonials/{id}/toggle', [TestimonialController::class, 'toggleStatus'])->name('admin.website.testimonials.toggle');
Route::delete('/admin/website/testimonials/{id}', [TestimonialController::class, 'destroy'])->name('admin.website.testimonials.destroy');

Route::get('/admin/website/gallery', function () {
    $galleries = Gallery::latest()->get();
    return view('admin.website.gallery.index', compact('galleries'));
});

Route::get('/admin/website/gallery/create', function () {
    return view('admin.website.gallery.create');
});

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;

Route::prefix('admin/blog')->name('admin.blog.')->group(function () {
    // Categories
    Route::get('/categories', [BlogCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [BlogCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [BlogCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [BlogCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [BlogCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [BlogCategoryController::class, 'destroy'])->name('categories.destroy');

    // Articles
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
});

// Course Price Routes
Route::get('/admin/course-price/tiers', function () {
    return view('admin.course-price.tiers');
});

use App\Http\Controllers\Admin\LocationBandController;

Route::get('/admin/course-price/location-bands', [LocationBandController::class, 'index'])->name('admin.location_bands.index');
Route::get('/admin/course-price/location-bands/create', [LocationBandController::class, 'create'])->name('admin.location_bands.create');
Route::post('/admin/course-price/location-bands', [LocationBandController::class, 'store'])->name('admin.location_bands.store');
Route::get('/admin/course-price/location-bands/{id}/edit', [LocationBandController::class, 'edit'])->name('admin.location_bands.edit');
Route::put('/admin/course-price/location-bands/{id}', [LocationBandController::class, 'update'])->name('admin.location_bands.update');
Route::delete('/admin/course-price/location-bands/{id}', [LocationBandController::class, 'destroy'])->name('admin.location_bands.destroy');

// Log Routes
use App\Http\Controllers\Admin\UserLogController;

Route::get('/admin/logs/{type}', [UserLogController::class, 'index'])->name('admin.logs.index');


Route::post('/admin/website/gallery', function (Request $request) {
    $request->validate([
        'media_file' => 'required|file|max:10240', // 10MB max
        'media_type' => 'required|string',
        'media_title' => 'nullable|string',
        'alt_text' => 'nullable|string',
    ]);

    if ($request->hasFile('media_file')) {
        // Store to S3 disk
        $path = $request->file('media_file')->storePublicly('gallery', 's3');

        if (!$path) {
            return back()->with('error', 'Failed to upload media to S3. Check if your bucket exists and credentials are correct.');
        }

        Gallery::create([
            'media_type' => $request->input('media_type'),
            'media_title' => $request->input('media_title'),
            'alt_text' => $request->input('alt_text'),
            'file_path' => $path,
        ]);

        return redirect('/admin/website/gallery')->with('success', 'Media uploaded to S3 and saved to database successfully!');
    }

    return back()->with('error', 'No file was uploaded.');
});

Route::delete('/admin/website/gallery/{id}', function ($id) {
    $gallery = Gallery::findOrFail($id);
    \Illuminate\Support\Facades\Storage::disk('s3')->delete($gallery->file_path);
    $gallery->delete();
    return response()->json(['success' => true]);
});
