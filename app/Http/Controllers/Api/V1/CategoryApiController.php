<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CategoryApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/featured-categories",
     *      operationId="getFeaturedCategories",
     *      tags={"Categories"},
     *      summary="Get list of featured categories",
     *      description="Returns list of active featured categories.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="category_name", type="string", example="Accounting and Finance"),
     *                  @OA\Property(property="featured_image", type="string", example="https://bucket.s3.amazonaws.com/course_categories/image.jpg")
     *              ))
     *          )
     *      )
     * )
     */
    public function featuredCategories()
    {
        $cacheKey = 'api_featured_categories_v1';
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $categories = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            return CourseCategory::where('featured_category', '1')
                ->where('status', 'active')
                ->get()
                ->map(function ($category) {
                    $catArr = $category->toArray();
                    
                    if (!empty($catArr['featured_image']) && !filter_var($catArr['featured_image'], FILTER_VALIDATE_URL)) {
                        $imagePath = $catArr['featured_image'];
                        if (strpos($imagePath, '/') === false) {
                            $imagePath = 'course_categories/' . $imagePath;
                        }
                        $catArr['featured_image'] = Storage::disk('s3')->url($imagePath);
                    }

                    if (!empty($catArr['banner_image']) && !filter_var($catArr['banner_image'], FILTER_VALIDATE_URL)) {
                        $imagePath = $catArr['banner_image'];
                        if (strpos($imagePath, '/') === false) {
                            $imagePath = 'course_categories/' . $imagePath;
                        }
                        $catArr['banner_image'] = Storage::disk('s3')->url($imagePath);
                    }

                    return $catArr;
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
