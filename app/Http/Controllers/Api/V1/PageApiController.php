<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentNew;
use Illuminate\Support\Facades\Cache;

class PageApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/pages/{url}",
     *      operationId="getPageByUrl",
     *      tags={"Pages"},
     *      summary="Get static page details by URL slug",
     *      description="Returns page title, content, banner, menu_title and status for a given URL slug.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(response=404, description="Page not found")
     * )
     */
    public function show($url)
    {
        $cacheKey = "api_page_v1_{$url}";
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache (or fallback to database)
        $page = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () use ($url) {
            $record = ContentNew::where('url', $url)->where('status', '1')->first();
            if (!$record) {
                return null;
            }

            $arr = $record->toArray();
            
            // Generate full banner URL if banner exists
            if (!empty($arr['page_banner'])) {
                $arr['banner_url'] = $record->banner_url;
            } else {
                $arr['banner_url'] = null;
            }

            return $arr;
        });

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
