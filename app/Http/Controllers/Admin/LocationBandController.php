<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationBand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationBandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $venueMap = DB::table('venue')->pluck('venue_name', 'id');
            $bands = LocationBand::orderBy('id', 'desc')->get()->map(function($band) use ($venueMap) {
                // Resolve venue IDs to names
                $venueNames = [];
                if (!empty($band->venue)) {
                    $venueIds = array_map('trim', explode(',', $band->venue));
                    foreach ($venueIds as $vid) {
                        if (isset($venueMap[$vid])) {
                            $venueNames[] = $venueMap[$vid];
                        }
                    }
                }
                
                return [
                    'id' => $band->id,
                    'name' => $band->location_band_name,
                    'type' => $band->location_band_type,
                    'venues' => implode(', ', $venueNames),
                    'adjustment' => $band->adjustment,
                    'created' => $band->created_at ? $band->created_at->format('d/m/Y - H:i') : '-',
                    'updated' => $band->updated_at ? $band->updated_at->format('d/m/Y - H:i') : '-'
                ];
            });
            return response()->json(['success' => true, 'bands' => $bands]);
        }
        return view('admin.course-price.location-bands');
    }

    public function create()
    {
        $venues = DB::table('venue')->where('status', '1')->orderBy('venue_name')->get();
        return view('admin.course-price.location-bands_create', compact('venues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_band_name' => 'required|string|max:255',
            'location_band_type' => 'required|in:Plus,Minus',
            'adjustment' => 'required|numeric',
            'venue' => 'nullable|array'
        ]);

        LocationBand::create([
            'location_band_name' => $request->location_band_name,
            'location_band_type' => $request->location_band_type,
            'adjustment' => $request->adjustment,
            'venue' => $request->venue ? implode(', ', $request->venue) : ''
        ]);

        return response()->json(['success' => true, 'message' => 'Location Band created successfully']);
    }

    public function edit($id)
    {
        $band = LocationBand::findOrFail($id);
        $venues = DB::table('venue')->where('status', '1')->orderBy('venue_name')->get();
        return view('admin.course-price.location-bands_edit', compact('band', 'venues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'location_band_name' => 'required|string|max:255',
            'location_band_type' => 'required|in:Plus,Minus',
            'adjustment' => 'required|numeric',
            'venue' => 'nullable|array'
        ]);

        $band = LocationBand::findOrFail($id);
        $band->update([
            'location_band_name' => $request->location_band_name,
            'location_band_type' => $request->location_band_type,
            'adjustment' => $request->adjustment,
            'venue' => $request->venue ? implode(', ', $request->venue) : ''
        ]);

        return response()->json(['success' => true, 'message' => 'Location Band updated successfully']);
    }

    public function destroy($id)
    {
        LocationBand::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
