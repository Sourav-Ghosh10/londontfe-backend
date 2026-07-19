<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class VenueApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/venues",
     *      operationId="getAllVenues",
     *      tags={"Venues"},
     *      summary="Get list of all active venues",
     *      description="Returns list of active venues with second venue image.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="venue_name", type="string", example="Amsterdam"),
     *                  @OA\Property(property="venue_seo_name", type="string", example="amsterdam"),
     *                  @OA\Property(property="venue_image_second", type="string", example="https://londontfefiles.s3.amazonaws.com/venues/amsterdam.jpg")
     *              ))
     *          )
     *      )
     * )
     */
    public function index()
    {
        $cacheKey = 'api_all_venues_v1';
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $venues = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            return Venue::where('status', '1')
                ->orderBy('venue_name')
                ->get()
                ->map(function ($venue) {
                    $venueArr = $venue->toArray();
                    
                    if (!empty($venueArr['venue_image_second']) && !filter_var($venueArr['venue_image_second'], FILTER_VALIDATE_URL)) {
                        $imagePath = $venueArr['venue_image_second'];
                        if (strpos($imagePath, '/') === false) {
                            $imagePath = 'venues/' . $imagePath;
                        }
                        $venueArr['venue_image_second'] = Storage::disk('s3')->url($imagePath);
                    } else {
                        // Ensure it fallback to null or empty string if not set
                        $venueArr['venue_image_second'] = null;
                    }
                    
                    if (!empty($venueArr['banner_image']) && !filter_var($venueArr['banner_image'], FILTER_VALIDATE_URL)) {
                        $bannerPath = $venueArr['banner_image'];
                        if (strpos($bannerPath, '/') === false) {
                            $bannerPath = 'venues/banners/' . $bannerPath;
                        }
                        $venueArr['banner_image'] = Storage::disk('s3')->url($bannerPath);
                    }

                    return $venueArr;
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => $venues
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }

    public function show($slug)
    {
        $venue = Venue::where('venue_seo_name', $slug)->first();

        if (!$venue) {
            return response()->json([
                'success' => false,
                'message' => 'Venue not found'
            ], 404);
        }

        $venueArr = $venue->toArray();
        if (!empty($venueArr['venue_image_second']) && !filter_var($venueArr['venue_image_second'], FILTER_VALIDATE_URL)) {
            $imagePath = $venueArr['venue_image_second'];
            if (strpos($imagePath, '/') === false) {
                $imagePath = 'venues/' . $imagePath;
            }
            $venueArr['venue_image_second'] = Storage::disk('s3')->url($imagePath);
        }
        if (!empty($venueArr['banner_image']) && !filter_var($venueArr['banner_image'], FILTER_VALIDATE_URL)) {
            $bannerPath = $venueArr['banner_image'];
            if (strpos($bannerPath, '/') === false) {
                $bannerPath = 'venues/banners/' . $bannerPath;
            }
            $venueArr['banner_image'] = Storage::disk('s3')->url($bannerPath);
        }

        return response()->json([
            'success' => true,
            'data' => $venueArr
        ]);
    }
}
