<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Seo;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'user'])->orderBy('id', 'desc');

        // Status Filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            if ($status === 'Published') {
                $query->where(function($q) {
                    $q->where('status', '1')->orWhere('status', 'published');
                });
            } elseif ($status === 'Draft') {
                $query->where(function($q) {
                    $q->where('status', '0')->orWhere('status', 'draft');
                });
            } elseif ($status === 'Pending') {
                $query->whereNotIn('status', ['0', '1', 'published', 'draft']);
            }
        }

        // Category Filter
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('blog_category_id', $request->input('category'));
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('blog_title', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $blogs = $query->paginate(10)->withQueryString();
        $categories = BlogCategory::whereIn('status', ['active', '1'])->orderBy('category_name', 'asc')->get();

        return view('admin.blog.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::whereIn('status', ['active', '1'])->get();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $imagePath = '';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storePublicly('blogs', 's3') ?: '';
        }

        $dbStatus = strtolower($request->status) === 'published' ? '1' : '0';

        $blog = Blog::create([
            'blog_title' => $request->title,
            'blog_category_id' => $request->category,
            'category_id' => $request->category ?? 0,
            'course_id' => 0,
            'post_date' => $request->date ? date('Y-m-d', strtotime(str_replace('/', '-', $request->date))) : now()->format('Y-m-d'),
            'status' => $dbStatus,
            'content' => $request->content,
            'image' => $imagePath,
            'seo_name' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $request->title))
        ]);

        if ($request->meta_title || $request->meta_desc) {
            Seo::create([
                'title' => $request->meta_title,
                'meta_description' => $request->meta_desc,
                'page_type' => 'Blog',
                'reference_id' => $blog->id,
                'create_date' => now(),
                'last_updated' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::whereIn('status', ['active', '1'])->get();
        $seo = Seo::where('page_type', 'Blog')->where('reference_id', $blog->id)->first();
        return view('admin.blog.edit', compact('blog', 'categories', 'seo'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                Storage::disk('s3')->delete($blog->image);
            }
            $blog->image = $request->file('image')->storePublicly('blogs', 's3') ?: $blog->image;
        }

        $dbStatus = strtolower($request->status) === 'published' ? '1' : '0';

        $blog->update([
            'blog_title' => $request->title,
            'blog_category_id' => $request->category,
            'category_id' => $request->category ?? $blog->category_id,
            'course_id' => $blog->course_id ?? 0,
            'post_date' => $request->date ? date('Y-m-d', strtotime(str_replace('/', '-', $request->date))) : $blog->post_date,
            'status' => $dbStatus,
            'content' => $request->content,
            'seo_name' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $request->title))
        ]);

        $seo = Seo::where('page_type', 'Blog')->where('reference_id', $blog->id)->first();
        if ($seo) {
            $seo->update([
                'title' => $request->meta_title,
                'meta_description' => $request->meta_desc,
                'last_updated' => now()
            ]);
        } else if ($request->meta_title || $request->meta_desc) {
            Seo::create([
                'title' => $request->meta_title,
                'meta_description' => $request->meta_desc,
                'page_type' => 'Blog',
                'reference_id' => $blog->id,
                'create_date' => now(),
                'last_updated' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && !str_starts_with($blog->image, 'http')) {
            Storage::disk('s3')->delete($blog->image);
        }
        $blog->delete();
        Seo::where('page_type', 'Blog')->where('reference_id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
