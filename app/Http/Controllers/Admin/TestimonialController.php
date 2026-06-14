<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('id', 'desc')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'author' => $item->author_name,
                'description' => $item->testimonial_text,
                'authorInfo' => $item->author_description,
                'status' => $item->status === '1' ? 'Active' : 'Inactive',
            ];
        });
        return view('admin.website.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'testimonial_text' => 'required|string',
            'author_description' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        $testimonial = Testimonial::create([
            'author_name' => $request->author_name,
            'testimonial_text' => $request->testimonial_text,
            'author_description' => $request->author_description,
            'created_on' => now()->format('Y-m-d H:i:s'),
            'status' => $request->status === 'Active' ? '1' : '0',
        ]);

        return response()->json(['success' => true, 'testimonial' => $testimonial]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.website.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'author_name' => 'required|string|max:255',
            'testimonial_text' => 'required|string',
            'author_description' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        $testimonial->update([
            'author_name' => $request->author_name,
            'testimonial_text' => $request->testimonial_text,
            'author_description' => $request->author_description,
            'status' => $request->status === 'Active' ? '1' : '0',
        ]);

        return response()->json(['success' => true, 'testimonial' => $testimonial]);
    }

    /**
     * Toggle the active status of the testimonial.
     */
    public function toggleStatus($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->status = $testimonial->status === '1' ? '0' : '1';
        $testimonial->save();

        return response()->json([
            'success' => true,
            'status' => $testimonial->status === '1' ? 'Active' : 'Inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json(['success' => true]);
    }
}
