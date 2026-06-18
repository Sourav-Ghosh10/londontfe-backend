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

    /**
     * @OA\Get(
     *      path="/api/v1/course-filters",
     *      operationId="getCourseFilters",
     *      tags={"Courses"},
     *      summary="Get list of course filters",
     *      description="Returns list of categories, venues, and certifications for filtering.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )
     * )
     */
    public function filters()
    {
        $cacheKey = 'api_course_filters_v1';
        $cacheTtl = 3600; // 1 hour

        $filters = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            $categories = DB::table('category')
                ->select('id', 'category_name', 'category_seo_name')
                ->where('status', 'active')
                ->orderBy('category_name')
                ->get()
                ->toArray();

            $venues = DB::table('venue')
                ->select('id', 'venue_name', 'venue_seo_name', 'region')
                ->where('status', '1')
                ->orderBy('venue_name')
                ->get()
                ->toArray();

            $certifications = DB::table('accreditation_content')
                ->select('id', 'accreditation_name')
                ->where('status', '1')
                ->orderBy('display_order')
                ->get()
                ->toArray();

            return [
                'categories' => $categories,
                'venues' => $venues,
                'certifications' => $certifications,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $filters
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }

    /**
     * @OA\Get(
     *      path="/api/v1/courses",
     *      operationId="getCourses",
     *      tags={"Courses"},
     *      summary="Get list of courses based on filters",
     *      description="Returns paginated list of courses with schedules.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = DB::table('course_date_venue as cdv')
            ->join('course as c', 'cdv.course_id', '=', 'c.id')
            ->leftJoin('course_category_assoc as cca', 'c.id', '=', 'cca.course_id')
            ->leftJoin('category as cat', 'cca.category_id', '=', 'cat.id')
            ->leftJoin('venue as v', 'cdv.venue_id', '=', 'v.id')
            ->select(
                'cdv.id as schedule_id',
                'c.id as course_id',
                'c.course_name',
                'c.seo_name',
                'c.course_duration',
                'c.rating',
                'cat.category_name',
                'cat.category_seo_name',
                'cat.image_name',
                'cat.course_list_image',
                'cat.featured_image',
                'cdv.start_date',
                'cdv.venue as venue_name',
                'v.venue_seo_name'
            )
            ->where('c.status', '1')
            ->where('cdv.start_date', '>=', now()->format('Y-m-d'))
            ->groupBy('cdv.id', 'c.id', 'c.course_name', 'c.seo_name', 'c.course_duration', 'c.rating', 'cat.category_name', 'cat.category_seo_name', 'cat.image_name', 'cat.course_list_image', 'cat.featured_image', 'cdv.start_date', 'cdv.venue', 'v.venue_seo_name');

        // Apply Filters
        if ($request->has('category') && !empty($request->input('category'))) {
            $categories = explode(',', $request->input('category'));
            $query->whereIn('cat.category_seo_name', $categories);
        }

        if ($request->has('location') && !empty($request->input('location'))) {
            $locations = explode(',', $request->input('location'));
            $query->whereIn('v.venue_seo_name', $locations);
        }

        if ($request->has('certification') && !empty($request->input('certification'))) {
            if ($request->input('certification') === 'yes') {
                $query->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('course_accreditation_assoc as caa')
                        ->whereColumn('caa.course_id', 'c.id');
                });
            } else {
                $certifications = explode(',', $request->input('certification'));
                $query->whereExists(function ($q) use ($certifications) {
                    $q->select(DB::raw(1))
                        ->from('course_accreditation_assoc as caa')
                        ->join('accreditation_content as ac', 'caa.accreditation_id', '=', 'ac.id')
                        ->whereColumn('caa.course_id', 'c.id')
                        ->whereIn('ac.id', $certifications);
                });
            }
        }

        // Apply Sorting & Date Filtering
        $sort = $request->input('sort', 'date_asc');
        switch ($sort) {
            case 'alpha_asc':
                $query->orderBy('c.course_name', 'asc');
                break;
            case 'alpha_desc':
                $query->orderBy('c.course_name', 'desc');
                break;
            case 'this_week':
                $query->whereBetween('cdv.start_date', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])
                      ->orderBy('cdv.start_date', 'asc');
                break;
            case 'this_month':
                $query->whereMonth('cdv.start_date', now()->month)
                      ->whereYear('cdv.start_date', now()->year)
                      ->orderBy('cdv.start_date', 'asc');
                break;
            case 'upcoming_month':
                $nextMonth = now()->addMonth();
                $query->whereMonth('cdv.start_date', $nextMonth->month)
                      ->whereYear('cdv.start_date', $nextMonth->year)
                      ->orderBy('cdv.start_date', 'asc');
                break;
            case 'date_asc':
            default:
                $query->orderBy('cdv.start_date', 'asc');
                break;
        }

        $perPage = $request->input('per_page', 9);
        $results = $query->paginate($perPage);

        // Format dates, duration, and images
        $results->getCollection()->transform(function ($item) {
            $startDate = \Carbon\Carbon::parse($item->start_date);
            $duration = (int) $item->course_duration;
            $endDate = $startDate->copy()->addDays($duration > 0 ? $duration - 1 : 0);

            $item->formatted_date = $startDate->format('d') . ' - ' . $endDate->format('d M Y');
            $item->duration_text = $duration . ' Days';

            // Format images, prioritizing image_name (for graphic icons) over list/featured photos
            $imageToUse = $item->featured_image;
            if ($imageToUse && !filter_var($imageToUse, FILTER_VALIDATE_URL)) {
                if (strpos($imageToUse, '/') === false) {
                    $imageToUse = 'course_categories/' . $imageToUse;
                }
                $item->category_image = \Illuminate\Support\Facades\Storage::disk('s3')->url($imageToUse);
            } else {
                $item->category_image = $imageToUse;
            }

            unset($item->image_name);
            unset($item->course_list_image);
            unset($item->featured_image);

            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $results
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
