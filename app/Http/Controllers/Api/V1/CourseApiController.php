<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CourseApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/popular-courses",
     *      operationId="getPopularCourses",
     *      tags={"Courses"},
     *      summary="Get list of popular courses",
     *      description="Returns list of popular courses based on featured status.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="course_name", type="string", example="A-Z of Credit Control"),
     *                  @OA\Property(property="seo_name", type="string", example="a-z-of-credit-control"),
     *                  @OA\Property(property="category_seo_name", type="string", example="accounting-and-finance"),
     *                  @OA\Property(property="featured_image", type="string", example="https://bucket.s3.amazonaws.com/course_categories/image.jpg"),
     *                  @OA\Property(property="parent_category", type="integer", example=0)
     *              ))
     *          )
     *      )
     * )
     */
    public function popularCourses()
    {
        $cacheKey = 'api_popular_courses_v1';
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $courses = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            return DB::table('course')
                ->select(
                    'course.course_name',
                    'course.seo_name',
                    'category.category_seo_name',
                    'category.featured_image',
                    'category.parent_category'
                )
                ->join('course_category_assoc', 'course_category_assoc.course_id', '=', 'course.id')
                ->join('category', 'course_category_assoc.category_id', '=', 'category.id')
                ->where('course.is_featured', 'yes')
                ->where('course.status', '1')
                ->where('category.featured_category', '1')
                ->groupBy(
                    'course.course_name',
                    'course.seo_name',
                    'category.category_seo_name',
                    'category.featured_image',
                    'category.parent_category'
                )
                ->get()
                ->map(function ($course) {
                    if ($course->featured_image && !filter_var($course->featured_image, FILTER_VALIDATE_URL)) {
                        // Prepend folder if it's just a filename (old data)
                        $imagePath = $course->featured_image;
                        if (strpos($imagePath, '/') === false) {
                            $imagePath = 'course_categories/' . $imagePath;
                        }
                        $course->featured_image = Storage::disk('s3')->url($imagePath);
                    }
                    return (array) $course;
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => $courses
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
