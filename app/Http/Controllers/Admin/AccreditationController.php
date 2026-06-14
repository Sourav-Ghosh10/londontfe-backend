<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccreditationContent;
use Illuminate\Support\Facades\Storage;

class AccreditationController extends Controller
{
    /**
     * Display a listing of accreditation bodies.
     */
    public function index()
    {
        $items = AccreditationContent::orderBy('display_order', 'asc')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => trim($item->accreditation_name),
                'content' => trim($item->content),
                'logo' => $item->logo_url,
                'heading' => trim($item->heading),
                'members' => trim($item->members),
                'countries' => trim($item->countries),
                'chapters' => trim($item->chapters),
                'tagline' => trim($item->tag_line),
                'status' => $item->status === 1 ? 'Active' : 'Inactive',
            ];
        });

        return view('admin.website.accreditation.index', compact('items'));
    }

    /**
     * Show the form for creating a new accreditation body.
     */
    public function create()
    {
        return view('admin.website.accreditation.create');
    }

    /**
     * Store a newly created accreditation body in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'accreditation_name' => 'required|string|max:255',
            'content' => 'required|string',
            'heading' => 'nullable|string|max:255',
            'tag_line' => 'nullable|string|max:255',
            'members' => 'nullable|string|max:255',
            'countries' => 'nullable|string|max:255',
            'chapters' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'logo' => 'nullable|image|max:10240',
        ]);

        $logoPath = '';
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->storePublicly('accreditations', 's3') ?: '';
        }

        $item = AccreditationContent::create([
            'accreditation_name' => $request->accreditation_name,
            'content' => $request->content,
            'heading' => $request->heading ?? '',
            'tag_line' => $request->tag_line ?? '',
            'members' => $request->members ?? '',
            'countries' => $request->countries ?? '',
            'chapters' => $request->chapters ?? '',
            'status' => $request->status === 'Active' ? 1 : 0,
            'logo' => $logoPath,
            'display_order' => (AccreditationContent::max('display_order') ?: 0) + 1,
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    /**
     * Show the form for editing the specified accreditation body.
     */
    public function edit($id)
    {
        $item = AccreditationContent::findOrFail($id);
        return view('admin.website.accreditation.edit', compact('item'));
    }

    /**
     * Update the specified accreditation body in storage.
     */
    public function update(Request $request, $id)
    {
        $item = AccreditationContent::findOrFail($id);

        $request->validate([
            'accreditation_name' => 'required|string|max:255',
            'content' => 'required|string',
            'heading' => 'nullable|string|max:255',
            'tag_line' => 'nullable|string|max:255',
            'members' => 'nullable|string|max:255',
            'countries' => 'nullable|string|max:255',
            'chapters' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'logo' => 'nullable|image|max:10240',
        ]);

        $logoPath = $item->logo;
        if ($request->hasFile('logo')) {
            if ($item->logo && !str_starts_with($item->logo, 'http')) {
                Storage::disk('s3')->delete($item->logo);
            }
            $logoPath = $request->file('logo')->storePublicly('accreditations', 's3') ?: '';
        }

        $item->update([
            'accreditation_name' => $request->accreditation_name,
            'content' => $request->content,
            'heading' => $request->heading ?? '',
            'tag_line' => $request->tag_line ?? '',
            'members' => $request->members ?? '',
            'countries' => $request->countries ?? '',
            'chapters' => $request->chapters ?? '',
            'status' => $request->status === 'Active' ? 1 : 0,
            'logo' => $logoPath,
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    /**
     * Toggle status.
     */
    public function toggleStatus($id)
    {
        $item = AccreditationContent::findOrFail($id);
        $item->status = $item->status === 1 ? 0 : 1;
        $item->save();

        return response()->json([
            'success' => true,
            'status' => $item->status === 1 ? 'Active' : 'Inactive'
        ]);
    }

    /**
     * Remove the specified accreditation body from storage.
     */
    public function destroy($id)
    {
        $item = AccreditationContent::findOrFail($id);

        if ($item->logo && !str_starts_with($item->logo, 'http')) {
            Storage::disk('s3')->delete($item->logo);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }
}
