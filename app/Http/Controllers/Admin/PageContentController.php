<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentNew;
use App\Models\Seo;
use Illuminate\Support\Facades\Storage;

class PageContentController extends Controller
{
    /**
     * Display a listing of content pages.
     */
    public function index()
    {
        $pages = ContentNew::orderBy('title', 'asc')->get()->map(function($page) {
            return [
                'id' => $page->id,
                'title' => trim($page->title),
                'content' => strip_tags($page->content),
                'menu_title' => trim($page->menu_title),
                'status' => $page->status === '1' ? 'Active' : 'Inactive',
            ];
        });

        return view('admin.website.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new content page.
     */
    public function create()
    {
        $parentPages = ContentNew::where('parent_page_id', 0)->where('status', '1')->orderBy('title', 'asc')->get();
        return view('admin.website.pages.create', compact('parentPages'));
    }

    /**
     * Store a newly created content page in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'menu_title' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'parent_page_id' => 'nullable|integer',
            'page_banner' => 'nullable|image|max:10240',
            'seo_title' => 'required|string|max:255',
            'meta_description' => 'required|string',
        ]);

        $bannerPath = '';
        if ($request->hasFile('page_banner')) {
            $bannerPath = $request->file('page_banner')->storePublicly('pages/banners', 's3') ?: '';
        }

        $page = ContentNew::create([
            'title' => $request->title,
            'content' => $request->content,
            'menu_title' => $request->menu_title ?? '',
            'url' => $request->url,
            'status' => $request->status === 'Active' ? '1' : '0',
            'parent_page_id' => $request->parent_page_id ?? 0,
            'page_banner' => $bannerPath,
        ]);

        Seo::create([
            'title' => $request->seo_title,
            'meta_description' => $request->meta_description,
            'page_type' => 'Content',
            'reference_id' => $page->id,
            'meta_keywords' => '',
            'status' => '1',
            'create_date' => now(),
        ]);

        return response()->json(['success' => true, 'page' => $page]);
    }

    /**
     * Show the form for editing the specified content page.
     */
    public function edit($id)
    {
        $page = ContentNew::findOrFail($id);
        $parentPages = ContentNew::where('parent_page_id', 0)->where('id', '!=', $id)->where('status', '1')->orderBy('title', 'asc')->get();
        $seo = Seo::where('page_type', 'Content')->where('reference_id', $id)->first();
        return view('admin.website.pages.edit', compact('page', 'parentPages', 'seo'));
    }

    /**
     * Update the specified content page in storage.
     */
    public function update(Request $request, $id)
    {
        $page = ContentNew::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'menu_title' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'parent_page_id' => 'nullable|integer',
            'page_banner' => 'nullable|image|max:10240',
            'seo_title' => 'required|string|max:255',
            'meta_description' => 'required|string',
        ]);

        $bannerPath = $page->page_banner;
        if ($request->hasFile('page_banner')) {
            if ($page->page_banner && !str_starts_with($page->page_banner, 'http')) {
                Storage::disk('s3')->delete($page->page_banner);
            }
            $bannerPath = $request->file('page_banner')->storePublicly('pages/banners', 's3') ?: '';
        }

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
            'menu_title' => $request->menu_title ?? '',
            'url' => $request->url,
            'status' => $request->status === 'Active' ? '1' : '0',
            'parent_page_id' => $request->parent_page_id ?? 0,
            'page_banner' => $bannerPath,
        ]);

        Seo::updateOrCreate(
            ['page_type' => 'Content', 'reference_id' => $id],
            [
                'title' => $request->seo_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => '',
                'status' => '1',
                'create_date' => $page->create_date ?? now(),
            ]
        );

        return response()->json(['success' => true, 'page' => $page]);
    }

    /**
     * Toggle the active status of the page.
     */
    public function toggleStatus($id)
    {
        $page = ContentNew::findOrFail($id);
        $page->status = $page->status === '1' ? '0' : '1';
        $page->save();

        return response()->json([
            'success' => true,
            'status' => $page->status === '1' ? 'Active' : 'Inactive'
        ]);
    }

    /**
     * Remove the specified content page from storage.
     */
    public function destroy($id)
    {
        $page = ContentNew::findOrFail($id);

        if ($page->page_banner && !str_starts_with($page->page_banner, 'http')) {
            Storage::disk('s3')->delete($page->page_banner);
        }

        $page->delete();
        Seo::where('page_type', 'Content')->where('reference_id', $id)->delete();

        return response()->json(['success' => true]);
    }
}
