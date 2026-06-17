<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\CourseCategory::orderBy('create_date', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $query->where('category_name', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === 'active' ? 'active' : 'inactive');
        }

        $categories = $query->paginate(15)->withQueryString();
        return view('admin.courses.categories', compact('categories'));
    }

    public function create()
    {
        return view('admin.courses.categories_create');
    }

    public function edit($id)
    {
        $category = \App\Models\CourseCategory::findOrFail($id);
        return view('admin.courses.categories_edit', compact('category'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'meta_title'       => 'required|string|max:255',
            'meta_description' => 'required|string|max:1000',
            'featured_image'   => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'banner_image'     => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ], [
            'name.required'             => 'Category name is required.',
            'name.max'                  => 'Category name must not exceed 255 characters.',
            'meta_title.required'       => 'SEO title is required.',
            'meta_title.max'            => 'SEO title must not exceed 255 characters.',
            'meta_description.required' => 'Meta description is required.',
            'meta_description.max'      => 'Meta description must not exceed 1000 characters.',
            'featured_image.mimes'      => 'Featured image must be a JPG, PNG, WEBP, or GIF.',
            'featured_image.max'        => 'Featured image must not exceed 10MB.',
            'banner_image.mimes'        => 'Banner image must be a JPG, PNG, WEBP, or GIF.',
            'banner_image.max'          => 'Banner image must not exceed 10MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = [
            'category_name'      => $request->input('name') ?? '',
            'category_seo_name'  => \Illuminate\Support\Str::slug($request->input('name') ?? ''),
            'category_tag_line'  => $request->input('tagline') ?? '',
            'level_page_text'    => $request->input('level_page_text') ?? '',
            'parent_category'    => $request->input('parent_category') ?? '',
            'image_name'         => '',
            'course_list_image'  => '',
            'course_details_image' => '',
            'meta_title'         => $request->input('meta_title') ?? '',
            'meta_description'   => $request->input('meta_description') ?? '',
            'meta_keyword'       => $request->input('meta_keywords') ?? '',
            'category_content'   => $request->input('content') ?? '',
            'category_txt'       => $request->input('about') ?? '',
            'is_3_for_2_offer'   => strtolower($request->input('is_3_for_2') ?? '') === 'yes' ? 'active' : 'inactive',
            'status'             => 'active',
            'featured_category'  => '0',
            'featured_image'     => '',
            'banner_image'       => '',
            'create_date'        => now(),
            'last_updated'       => now(),
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->storePublicly('course_categories', 's3') ?: '';
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->storePublicly('course_categories', 's3') ?: '';
        }

        \App\Models\CourseCategory::create($data);

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_featured_categories_v1');

        return response()->json(['success' => true, 'message' => 'Category created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\CourseCategory::findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'meta_title'       => 'required|string|max:255',
            'meta_description' => 'required|string|max:1000',
            'featured_image'   => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'banner_image'     => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ], [
            'name.required'             => 'Category name is required.',
            'meta_title.required'       => 'SEO title is required.',
            'meta_description.required' => 'Meta description is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'category_name'     => $request->input('name') ?? '',
            'category_seo_name' => \Illuminate\Support\Str::slug($request->input('name') ?? ''),
            'category_tag_line' => $request->input('tagline') ?? '',
            'level_page_text'   => $request->input('level_page_text') ?? '',
            'meta_title'        => $request->input('meta_title') ?? '',
            'meta_description'  => $request->input('meta_description') ?? '',
            'meta_keyword'      => $request->input('meta_keywords') ?? '',
            'category_content'  => $request->input('content') ?? '',
            'category_txt'      => $request->input('about') ?? '',
            'is_3_for_2_offer'  => strtolower($request->input('is_3_for_2') ?? '') === 'yes' ? 'active' : 'inactive',
            'status'            => $request->input('status', 'active'),
            'featured_category' => $request->input('featured_category', $category->featured_category),
            'last_updated'      => now(),
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->storePublicly('course_categories', 's3') ?: $category->featured_image;
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->storePublicly('course_categories', 's3') ?: $category->banner_image;
        }

        $category->update($data);

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_featured_categories_v1');

        return response()->json(['success' => true, 'message' => 'Category updated successfully!']);
    }

    public function destroy($id)
    {
        $category = \App\Models\CourseCategory::findOrFail($id);
        
        // Optionally delete files from S3 here
        // if ($category->featured_image) { \Illuminate\Support\Facades\Storage::disk('s3')->delete($category->featured_image); }
        // if ($category->banner_image) { \Illuminate\Support\Facades\Storage::disk('s3')->delete($category->banner_image); }
        
        $category->delete();

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_featured_categories_v1');

        return response()->json(['success' => true]);
    }

    public function toggleFeatured($id)
    {
        $category = \App\Models\CourseCategory::findOrFail($id);
        $category->featured_category = $category->featured_category == '1' ? '0' : '1';
        $category->save();

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_featured_categories_v1');

        return response()->json(['success' => true, 'is_featured' => $category->featured_category == '1']);
    }
}
