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

Route::get('/admin/courses/popular', function () {
    return view('admin.courses.popular');
});

use App\Http\Controllers\Admin\CourseCategoryController;

Route::get('/admin/courses/categories', [CourseCategoryController::class, 'index']);
Route::get('/admin/courses/categories/create', [CourseCategoryController::class, 'create']);
Route::post('/admin/courses/categories', [CourseCategoryController::class, 'store']);
Route::get('/admin/courses/categories/{id}/edit', [CourseCategoryController::class, 'edit']);
Route::put('/admin/courses/categories/{id}', [CourseCategoryController::class, 'update']);
Route::delete('/admin/courses/categories/{id}', [CourseCategoryController::class, 'destroy']);
Route::patch('/admin/courses/categories/{id}/toggle-featured', [CourseCategoryController::class, 'toggleFeatured']);

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

Route::get('/admin/courses/send-outline', [\App\Http\Controllers\Admin\SendOutlineController::class, 'index'])->name('admin.courses.send-outline');
Route::post('/admin/courses/send-outline', [\App\Http\Controllers\Admin\SendOutlineController::class, 'send'])->name('admin.courses.send-outline.send');
Route::get('/admin/courses/send-outline/dates', [\App\Http\Controllers\Admin\SendOutlineController::class, 'getDates'])->name('admin.courses.send-outline.dates');

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
    $galleries = Gallery::latest()->get();
    return view('admin.website.gallery.index', compact('galleries'));
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
    // \Illuminate\Support\Facades\Storage::disk('s3')->delete($gallery->file_path);
    $gallery->delete();
    return response()->json(['success' => true]);
});
