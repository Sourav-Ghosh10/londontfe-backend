<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seo;
use Illuminate\Support\Facades\Cache;

class SeoApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/seo",
     *      operationId="getSeoData",
     *      tags={"SEO"},
     *      summary="Get SEO data for a page",
     *      description="Returns SEO data for a specific page type and reference ID.",
     *      @OA\Parameter(
     *          name="page_type",
     *          in="query",
     *          description="Type of the page (e.g., Home, Course, Category)",
     *          required=false,
     *          @OA\Schema(type="string", default="Home")
     *      ),
     *      @OA\Parameter(
     *          name="reference_id",
     *          in="query",
     *          description="Reference ID for the page (e.g., 0 for Home)",
     *          required=false,
     *          @OA\Schema(type="integer", default=0)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="title", type="string", example="London TFE"),
     *                  @OA\Property(property="meta_keywords", type="string", example="training, courses"),
     *                  @OA\Property(property="meta_description", type="string", example="London TFE offers various training courses.")
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $pageType = $request->query('page_type', 'Home');
        $referenceId = $request->query('reference_id', 0);

        $cacheKey = "api_seo_{$pageType}_{$referenceId}_v1";
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $seoData = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () use ($pageType, $referenceId) {
            $seo = Seo::where('page_type', $pageType)
                ->where('reference_id', $referenceId)
                ->where('status', '1')
                ->first();

            if (!$seo) {
                return null;
            }

            return [
                'title' => $seo->title,
                'meta_keywords' => $seo->meta_keywords,
                'meta_description' => $seo->meta_description,
            ];
        });

        if (!$seoData) {
            return response()->json([
                'success' => false,
                'message' => 'SEO data not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $seoData
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
