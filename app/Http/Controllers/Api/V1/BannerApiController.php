<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BannerSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Banner API",
 *      description="API for retrieving active banners with Redis caching",
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 */
class BannerApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/banners",
     *      operationId="getBannersList",
     *      tags={"Banners"},
     *      summary="Get list of active banners",
     *      description="Returns list of active banners (id, title, image, link). Includes Redis and Cloudflare caching.",

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="title", type="string", example="Summer Sale"),
     *                  @OA\Property(property="image", type="string", example="https://bucket.s3.amazonaws.com/banners/image.jpg"),
     *                  @OA\Property(property="link", type="string", example="https://example.com/promo")
     *              ))
     *          )
     *      ),
     *      @OA\Response(response=429, description="Too Many Requests")
     * )
     */
    public function index()
    {
        $cacheKey = 'api_active_banners_v1';
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $banners = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            return BannerSlider::updateApiCache();
        });

        // Cloudflare & Browser Caching headers
        return response()->json([
            'success' => true,
            'data' => $banners
        ])
        ->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
