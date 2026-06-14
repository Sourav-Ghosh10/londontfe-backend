<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerSlider;
use Illuminate\Support\Facades\Storage;

class BannerSliderController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index()
    {
        $banners = BannerSlider::orderBy('sequence', 'asc')->get()->map(function($banner) {
            return [
                'id' => $banner->id,
                'desktop' => $banner->image_url,
                'mobile' => $banner->mobile_image_url,
                'alt' => $banner->alt_tag,
                'url' => $banner->url,
                'sequence' => $banner->sequence,
                'status' => $banner->status,
                'created_at' => $banner->created_at ? $banner->created_at->format('d/m/Y - H:i') : ''
            ];
        });
        return view('admin.website.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.website.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'alt_tag' => 'required|string|max:50',
            'sequence' => 'required|integer|min:1',
            'url' => 'nullable|url|max:350',
            'status' => 'required|in:Active,Inactive',
            'image' => 'required|image|max:10240', // 10MB max
            'mobile_image' => 'required|image|max:10240', // 10MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storePublicly('banners', 's3');
            if (!$imagePath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload desktop banner to S3.'], 500);
            }
        }

        $mobileImagePath = null;
        if ($request->hasFile('mobile_image')) {
            $mobileImagePath = $request->file('mobile_image')->storePublicly('banners', 's3');
            if (!$mobileImagePath) {
                // Cleanup desktop image if mobile fails
                if ($imagePath) {
                    Storage::disk('s3')->delete($imagePath);
                }
                return response()->json(['success' => false, 'error' => 'Failed to upload mobile banner to S3.'], 500);
            }
        }

        $banner = BannerSlider::create([
            'image' => $imagePath,
            'mobile_image' => $mobileImagePath,
            'alt_tag' => $request->alt_tag,
            'sequence' => $request->sequence,
            'url' => $request->url,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'banner' => $banner]);
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit($id)
    {
        $banner = BannerSlider::findOrFail($id);
        return view('admin.website.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = BannerSlider::findOrFail($id);

        $request->validate([
            'alt_tag' => 'required|string|max:50',
            'sequence' => 'required|integer|min:1',
            'url' => 'nullable|url|max:350',
            'status' => 'required|in:Active,Inactive',
            'image' => 'nullable|image|max:10240',
            'mobile_image' => 'nullable|image|max:10240',
        ]);

        $imagePath = $banner->image;
        if ($request->hasFile('image')) {
            if ($banner->image && !str_starts_with($banner->image, 'http')) {
                Storage::disk('s3')->delete($banner->image);
            }
            $imagePath = $request->file('image')->storePublicly('banners', 's3');
            if (!$imagePath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload desktop banner to S3.'], 500);
            }
        }

        $mobileImagePath = $banner->mobile_image;
        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image && !str_starts_with($banner->mobile_image, 'http')) {
                Storage::disk('s3')->delete($banner->mobile_image);
            }
            $mobileImagePath = $request->file('mobile_image')->storePublicly('banners', 's3');
            if (!$mobileImagePath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload mobile banner to S3.'], 500);
            }
        }

        $banner->update([
            'image' => $imagePath,
            'mobile_image' => $mobileImagePath,
            'alt_tag' => $request->alt_tag,
            'sequence' => $request->sequence,
            'url' => $request->url,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'banner' => $banner]);
    }

    /**
     * Toggle the active status of the banner.
     */
    public function toggleStatus($id)
    {
        $banner = BannerSlider::findOrFail($id);
        $banner->status = $banner->status === 'Active' ? 'Inactive' : 'Active';
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->status
        ]);
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy($id)
    {
        $banner = BannerSlider::findOrFail($id);

        if ($banner->image && !str_starts_with($banner->image, 'http')) {
            Storage::disk('s3')->delete($banner->image);
        }

        if ($banner->mobile_image && !str_starts_with($banner->mobile_image, 'http')) {
            Storage::disk('s3')->delete($banner->mobile_image);
        }

        $banner->delete();

        return response()->json(['success' => true]);
    }
}
