<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    /**
     * Map region string from UI to database integer.
     */
    protected function mapRegionToDb($regionStr)
    {
        switch ($regionStr) {
            case 'Europe':
                return 1;
            case 'Middle East':
                return 2;
            case 'Rest of World':
            default:
                return 3;
        }
    }

    /**
     * Map database integer to UI region string.
     */
    protected function mapRegionToUi($regionId)
    {
        switch ((int)$regionId) {
            case 1:
                return 'Europe';
            case 2:
                return 'Middle East';
            default:
                return 'Rest of World';
        }
    }

    public function index(Request $request)
    {
        $query = Venue::orderBy('create_date', 'desc');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('venue_name', 'like', '%' . $search . '%')
                  ->orWhere('flag_image', 'like', '%' . $search . '%');
            });
        }

        // Region Filter
        if ($request->filled('region') && $request->input('region') !== 'all') {
            $regionVal = $request->input('region');
            $regionId = $this->mapRegionToDb($regionVal);
            $query->where('region', $regionId);
        }

        // Status Filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // If AJAX request, return JSON for dynamically rendering table/filtering
        if ($request->ajax() || $request->wantsJson()) {
            $venues = $query->get()->map(function($venue) {
                return [
                    'id' => $venue->id,
                    'name' => $venue->venue_name,
                    'flag' => $venue->flag_image ?: '',
                    'region' => $this->mapRegionToUi($venue->region),
                    'status' => $venue->status,
                    'sealsStatus' => $venue->seals_status === '1',
                ];
            });
            return response()->json([
                'success' => true,
                'venues' => $venues
            ]);
        }

        // Return normal view
        $venues = $query->paginate(15)->withQueryString();
        return view('admin.courses.venues', compact('venues'));
    }

    public function create()
    {
        return view('admin.courses.venues_create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:50',
            'flag'             => 'required|string|max:255',
            'region'           => 'required|string',
            'description'      => 'nullable|string',
            'featured_text'    => 'nullable|string',
            'status'           => 'required|in:0,1',
            'banner_image'     => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'meta_title'       => 'required|string|max:255',
            'meta_description' => 'required|string|max:1000',
        ], [
            'name.required'             => 'Venue name is required.',
            'name.max'                  => 'Venue name must not exceed 50 characters.',
            'flag.required'             => 'Country/Flag Name is required.',
            'region.required'           => 'Region is required.',
            'meta_title.required'       => 'Meta Title is required.',
            'meta_description.required' => 'Meta Description is required.',
            'banner_image.mimes'        => 'Banner image must be a JPG, PNG, WEBP, or GIF.',
            'banner_image.max'          => 'Banner image must not exceed 10MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $regionId = $this->mapRegionToDb($request->input('region'));

        $data = [
            'venue_name'          => $request->input('name'),
            'venue_seo_name'      => Str::slug($request->input('name')),
            'flag_image'          => $request->input('flag'),
            'region'              => $regionId,
            'venue_text'          => $request->input('description') ?? '',
            'venue_featured_text' => $request->input('featured_text') ?? '',
            'status'              => $request->input('status', '1'),
            'seals_status'        => '1',
            'meta_title'          => $request->input('meta_title'),
            'meta_description'    => $request->input('meta_description'),
            'venue_address'       => '',
            'venue_image'         => '',
            'banner_image'        => '',
            'venue_featured_image'=> '',
            'is_featured'         => '0',
            'venue_type'          => 'original',
            'create_date'         => now(),
            'last_updated'        => now(),
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->storePublicly('venues/banners', 's3') ?: '';
        }

        Venue::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Venue created successfully!'
        ]);
    }

    public function show($id)
    {
        $venue = Venue::findOrFail($id);
        
        // Map database fields to what details view expects
        $mappedVenue = [
            'id' => $venue->id,
            'name' => $venue->venue_name,
            'flag' => $venue->flag_image,
            'region' => $this->mapRegionToUi($venue->region),
            'image' => $venue->banner_image ? \Illuminate\Support\Facades\Storage::disk('s3')->url($venue->banner_image) : 'https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=800&q=80',
            'description' => $venue->venue_text,
            'featuredText' => $venue->venue_featured_text,
            'metaTitle' => $venue->meta_title,
            'metaDesc' => $venue->meta_description,
        ];

        // Format path for the banner image - check if it is S3 stored
        if ($venue->banner_image && !Str::startsWith($venue->banner_image, 'http')) {
            $mappedVenue['image'] = \Illuminate\Support\Facades\Storage::disk('s3')->url($venue->banner_image);
        } elseif ($venue->banner_image) {
            $mappedVenue['image'] = $venue->banner_image;
        } else {
            // Fallback image based on name or generic city
            $mappedVenue['image'] = 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80';
        }

        return view('admin.courses.venues_view', compact('mappedVenue'));
    }

    public function edit($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->region_name = $this->mapRegionToUi($venue->region);
        return view('admin.courses.venues_edit', compact('venue'));
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:50',
            'flag'             => 'required|string|max:255',
            'region'           => 'required|string',
            'description'      => 'nullable|string',
            'featured_text'    => 'nullable|string',
            'status'           => 'required|in:0,1',
            'banner_image'     => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'meta_title'       => 'required|string|max:255',
            'meta_description' => 'required|string|max:1000',
        ], [
            'name.required'             => 'Venue name is required.',
            'name.max'                  => 'Venue name must not exceed 50 characters.',
            'flag.required'             => 'Country/Flag Name is required.',
            'region.required'           => 'Region is required.',
            'meta_title.required'       => 'Meta Title is required.',
            'meta_description.required' => 'Meta Description is required.',
            'banner_image.mimes'        => 'Banner image must be a JPG, PNG, WEBP, or GIF.',
            'banner_image.max'          => 'Banner image must not exceed 10MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $regionId = $this->mapRegionToDb($request->input('region'));

        $data = [
            'venue_name'          => $request->input('name'),
            'venue_seo_name'      => Str::slug($request->input('name')),
            'flag_image'          => $request->input('flag'),
            'region'              => $regionId,
            'venue_text'          => $request->input('description') ?? '',
            'venue_featured_text' => $request->input('featured_text') ?? '',
            'status'              => $request->input('status', '1'),
            'meta_title'          => $request->input('meta_title'),
            'meta_description'    => $request->input('meta_description'),
            'last_updated'        => now(),
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->storePublicly('venues/banners', 's3') ?: $venue->banner_image;
        }

        $venue->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Venue updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venue deleted successfully!'
        ]);
    }
}
