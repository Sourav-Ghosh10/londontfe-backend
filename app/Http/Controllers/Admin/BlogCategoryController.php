<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Seo;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $categories = BlogCategory::orderBy('id', 'desc')->get()->map(function ($cat) {
                $seo = Seo::where('page_type', 'Blog_category')->where('reference_id', $cat->id)->first();
                return [
                    'id' => $cat->id,
                    'name' => $cat->category_name,
                    'slug' => $cat->blog_cate_slug,
                    'title' => $seo ? $seo->title : '',
                    'meta' => $seo ? $seo->meta_description : '',
                    'articles' => $cat->blogs()->count(),
                    'status' => in_array(strtolower($cat->status), ['active', '1']) ? 'Active' : 'Inactive'
                ];
            });
            return response()->json(['success' => true, 'categories' => $categories]);
        }
        return view('admin.blog.categories');
    }

    public function create()
    {
        return view('admin.blog.categories_create');
    }

    public function store(Request $request)
    {
        $category = BlogCategory::create([
            'category_name' => $request->name,
            'blog_cate_slug' => $request->slug,
            'status' => strtolower($request->status) === 'inactive' ? 'inactive' : 'active',
            'create_date' => now(),
            'last_updated' => now()
        ]);

        Seo::create([
            'title' => $request->title,
            'meta_description' => $request->meta,
            'page_type' => 'Blog_category',
            'reference_id' => $category->id,
            'create_date' => now(),
            'last_updated' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        // Not requested yet, but we will create view if needed. For now returning fallback json
        $category = BlogCategory::findOrFail($id);
        $seo = Seo::where('page_type', 'Blog_category')->where('reference_id', $category->id)->first();
        return view('admin.blog.categories_edit', compact('category', 'seo'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        $updateData = [
            'category_name' => $request->name,
            'blog_cate_slug' => $request->slug,
            'last_updated' => now()
        ];
        if ($request->has('status')) {
            $updateData['status'] = strtolower($request->status) === 'inactive' ? 'inactive' : 'active';
        }
        $category->update($updateData);

        $seo = Seo::where('page_type', 'Blog_category')->where('reference_id', $category->id)->first();
        if ($seo) {
            $seo->update([
                'title' => $request->title,
                'meta_description' => $request->meta,
                'last_updated' => now()
            ]);
        } else {
            Seo::create([
                'title' => $request->title,
                'meta_description' => $request->meta,
                'page_type' => 'Blog_category',
                'reference_id' => $category->id,
                'create_date' => now(),
                'last_updated' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();
        Seo::where('page_type', 'Blog_category')->where('reference_id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
