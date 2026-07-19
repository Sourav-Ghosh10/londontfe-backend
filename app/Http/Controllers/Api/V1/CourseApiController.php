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
                'v.id as venue_id',
                'v.venue_seo_name',
                'c.price_tier_id',
                'pt.base_rate',
                'pt.daily_rate'
            )
            ->leftJoin('price_tier as pt', 'c.price_tier_id', '=', 'pt.id')
            ->where('c.status', '1')
            ->where('cdv.start_date', '>=', now()->format('Y-m-d'))
            ->groupBy('cdv.id', 'c.id', 'c.course_name', 'c.seo_name', 'c.course_duration', 'c.rating', 'cat.category_name', 'cat.category_seo_name', 'cat.image_name', 'cat.course_list_image', 'cat.featured_image', 'cdv.start_date', 'cdv.venue', 'v.id', 'v.venue_seo_name', 'c.price_tier_id', 'pt.base_rate', 'pt.daily_rate');

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

        // Fetch the promo code outside the loop for performance
        $promo = $request->has('coupon') ? \App\Models\Promocode::where('code', $request->coupon)->first() : null;

        // Format dates, duration, and images
        $results->getCollection()->transform(function ($item) use ($promo) {
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

            // Calculate dynamic price
            $item->price = 0;
            $item->original_price = 0;
            $item->discount_value = 0;
            $item->discount_type = null;
            $item->is_coupon_valid = false;

            if (!empty($item->base_rate)) {
                $days = (int) ($item->course_duration ?? 0);
                $baseRate = (float) $item->base_rate * (round($days / 5));
                $dailyRate = (float) $item->daily_rate * $days;
                $originalPrice = round(($baseRate + $dailyRate) / 100) * 100;
                
                $item->original_price = $originalPrice;
                $item->price = $originalPrice;

                if ($promo) {
                    $isValid = true;
                    if ($promo->type === 'Specific') {
                        $isValid = true;
                        if ($promo->course_id && $promo->course_id != $item->course_id) {
                            $isValid = false;
                        }
                        if ($promo->venue_id && $promo->venue_id != $item->venue_id) {
                            $isValid = false;
                        }
                        if ($promo->date && $promo->date != $item->start_date) {
                            $isValid = false;
                        }
                        if (!$promo->course_id && !$promo->venue_id && !$promo->date) {
                            $isValid = false; // specific but no target specified?
                        }
                    }
                    if ($isValid) {
                        $item->is_coupon_valid = true;
                        $item->discount_value = $promo->discount_value;
                        $item->discount_type = $promo->discount_type;
                        
                        if ($promo->discount_type === 'Percentage') {
                            $item->price = $originalPrice - ($originalPrice * ($promo->discount_value / 100));
                        } else {
                            $item->price = max(0, $originalPrice - $promo->discount_value);
                        }
                    }
                }
            }
            unset($item->base_rate);
            unset($item->daily_rate);
            unset($item->price_tier_id);

            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $results
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/course/{category_slug}/{course_slug}",
     *      operationId="getCourseDetails",
     *      tags={"Courses"},
     *      summary="Get details of a specific course",
     *      description="Returns detailed information about a course, including schedules, accreditations, and related courses.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Course not found"
     *      )
     * )
     */
    public function show($category_slug, $course_slug)
    {
        // Must use request() here since Request is not in method signature
        $request = request();
        $cacheKey = "api_course_details_{$category_slug}_{$course_slug}";
        $cacheTtl = 3600; // 1 hour

        $data = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () use ($category_slug, $course_slug) {
            // 1. Fetch course details
            $course = DB::table('course as c')
                ->join('course_category_assoc as cca', 'c.id', '=', 'cca.course_id')
                ->join('category as cat', 'cca.category_id', '=', 'cat.id')
                ->select(
                    'c.id',
                    'c.course_name',
                    'c.seo_name',
                    'c.overview',
                    'c.course_material',
                    'c.course_objective',
                    'c.course_meterial_content',
                    'c.wsa',
                    'c.cpd_hours',
                    'c.course_duration_type',
                    'c.course_duration',
                    'c.offer_type',
                    'c.offer_value',
                    'c.rating',
                    'c.course_tag_line',
                    'cat.id as cat_id',
                    'cat.category_name',
                    'cat.category_seo_name',
                    'cat.banner_image',
                    'cat.featured_image',
                    'cat.parent_category as cat_parent_id',
                    'c.price_tier_id',
                    'pt.base_rate',
                    'pt.daily_rate'
                )
                ->leftJoin('price_tier as pt', 'c.price_tier_id', '=', 'pt.id')
                ->where('c.seo_name', $course_slug)
                ->where('cat.category_seo_name', $category_slug)
                ->where('c.status', '1')
                ->first();

            if (!$course) {
                return null;
            }

            // Fix Banner Image
            if ($course->banner_image && !filter_var($course->banner_image, FILTER_VALIDATE_URL)) {
                if (strpos($course->banner_image, '/') === false) {
                    $course->banner_image = 'course_categories/' . $course->banner_image;
                }
                $course->banner_image_url = Storage::disk('s3')->url($course->banner_image);
            } else {
                $course->banner_image_url = $course->banner_image;
            }

            // Fix Featured Image
            if ($course->featured_image && !filter_var($course->featured_image, FILTER_VALIDATE_URL)) {
                if (strpos($course->featured_image, '/') === false) {
                    $course->featured_image = 'course_categories/' . $course->featured_image;
                }
                $course->featured_image_url = Storage::disk('s3')->url($course->featured_image);
            } else {
                $course->featured_image_url = $course->featured_image;
            }

            // Calculate dynamic price (base)
            $course->price = 0;
            $course->original_price = 0;
            $course->discount_value = 0;
            $course->discount_type = null;
            $course->is_coupon_valid = false;

            if (!empty($course->base_rate)) {
                $days = (int) ($course->course_duration ?? 0);
                $baseRate = (float) $course->base_rate * (round($days / 5));
                $dailyRate = (float) $course->daily_rate * $days;
                $course->original_price = round(($baseRate + $dailyRate) / 100) * 100;
                $course->price = $course->original_price;
            }
            unset($course->base_rate);
            unset($course->daily_rate);
            unset($course->price_tier_id);

            // 2. Fetch upcoming schedules
            $schedules = DB::table('course_date_venue as cdv')
                ->join('venue as v', 'cdv.venue_id', '=', 'v.id')
                ->select('cdv.id', 'cdv.start_date', 'v.venue_name as venue', 'v.id as venue_id', 'v.flag_image')
                ->where('cdv.course_id', $course->id)
                ->where('cdv.status', '1')
                ->where('cdv.start_date', '>=', now()->format('Y-m-d'))
                ->orderBy('cdv.start_date', 'asc')
                ->get()
                ->map(function ($schedule) use ($course) {
                    $startDate = \Carbon\Carbon::parse($schedule->start_date);
                    $duration = (int) $course->course_duration;

                    // Simple interval duration end date logic
                    $endDate = $startDate->copy()->addDays($duration > 0 ? $duration - 1 : 0);

                    $schedule->formatted_start_date = $startDate->format('d M Y');
                    $schedule->formatted_end_date = $endDate->format('d M Y');
                    $schedule->date_range = $startDate->format('d') . ' - ' . $endDate->format('d M Y');

                    return (array) $schedule;
                })
                ->toArray();

            // 3. Accreditations
            $accreditations = DB::table('course_accreditation_assoc as caa')
                ->join('accreditation_content as ac', 'caa.accreditation_id', '=', 'ac.id')
                ->select('ac.id', 'ac.accreditation_name', 'ac.logo', 'ac.content', 'ac.heading', 'ac.members', 'ac.countries', 'ac.chapters', 'ac.tag_line')
                ->where('caa.course_id', $course->id)
                ->where('ac.status', '1')
                ->get()
                ->map(function ($acc) {
                    if ($acc->logo && !filter_var($acc->logo, FILTER_VALIDATE_URL)) {
                        $logoPath = $acc->logo;
                        if (strpos($logoPath, '/') === false) {
                            $logoPath = 'accreditations/' . $logoPath;
                        }
                        $acc->logo = Storage::disk('s3')->url($logoPath);
                    }
                    return (array) $acc;
                })
                ->toArray();

            // 4. Course Advisor
            $advisor = DB::table('user_details as ud')
                ->join('user as u', 'ud.user_id', '=', 'u.id')
                ->join('category as c', function ($join) {
                    $join->on(DB::raw('FIND_IN_SET(c.id, ud.category_ids)'), '>', DB::raw('0'));
                })
                ->select(
                    'ud.first_name',
                    'ud.last_name',
                    'u.email',
                    'ud.phone_code',
                    'ud.phone',
                    'ud.contact_no_code',
                    'ud.contact_no',
                    'ud.whatsapp as ud_whatsapp',
                    'u.whats as u_whatsapp',
                    'ud.image_name',
                    'u.calender_link',
                    'c.category_name'
                )
                ->where(function ($query) use ($course) {
                    $query->where('c.id', $course->cat_id);
                    if ($course->cat_parent_id) {
                        $query->orWhere('c.id', $course->cat_parent_id);
                    }
                })
                ->where('ud.status', 'Active')
                ->where('u.status', '1')
                ->first();

            if ($advisor) {
                if ($advisor->image_name) {
                    $advisor->image_url = "https://www.londontfe.com/crm/uploads/staff_picture/" . $advisor->image_name;
                } else {
                    $advisor->image_url = "https://www.londontfe.com/assets/images/user_icon.png";
                }

                $advisor->whatsapp = $advisor->u_whatsapp ?: $advisor->ud_whatsapp;

                $advisor->phone_full = ($advisor->phone_code ?: '') . ($advisor->phone ?: '');
                if (!$advisor->phone_full && $advisor->contact_no) {
                    $advisor->phone_full = ($advisor->contact_no_code ?: '') . $advisor->contact_no;
                }

                $advisor = (array) $advisor;
            }

            // 5. SEO Details
            $seo = DB::table('seo')
                ->where('reference_id', $course->id)
                ->where('page_type', 'Course')
                ->where('status', '1')
                ->first();

            if ($seo) {
                $seo = (array) $seo;
            } else {
                // Default SEO logic
                $seo = [
                    'title' => ucwords(str_replace("-", " ", $course->seo_name)) . ' Course in ' . $course->category_name,
                    'meta_keywords' => 'Professional Training Courses London | London Training Excellence',
                    'meta_description' => "London Training for Excellence is delivering world class training programs in {$course->category_name}. Join the upcoming course on {$course->course_name}"
                ];
            }

            // 6. Related Courses (same category, active, upcoming schedules)
            $relatedCourses = DB::table('course as c')
                ->join('course_category_assoc as cca', 'c.id', '=', 'cca.course_id')
                ->join('course_date_venue as cdv', 'c.id', '=', 'cdv.course_id')
                ->join('venue as v', 'cdv.venue_id', '=', 'v.id')
                ->select('c.id', 'c.course_name', 'c.seo_name', 'c.course_duration', 'cdv.start_date', 'v.venue_name as venue')
                ->where('cca.category_id', $course->cat_id)
                ->where('c.id', '!=', $course->id)
                ->where('c.status', '1')
                ->where('cdv.status', '1')
                ->where('cdv.start_date', '>=', now()->format('Y-m-d'))
                ->orderBy('cdv.start_date', 'asc')
                ->groupBy('c.id', 'c.course_name', 'c.seo_name', 'c.course_duration', 'cdv.start_date', 'v.venue_name')
                ->limit(4)
                ->get()
                ->toArray();

            return [
                'course' => (array) $course,
                'schedules' => $schedules,
                'accreditations' => $accreditations,
                'advisor' => $advisor,
                'seo' => $seo,
                'related_courses' => $relatedCourses
            ];
        });

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        // Apply coupon logic outside the cache so we don't cache discounted prices globally
        $promo = $request->has('coupon') ? \App\Models\Promocode::where('code', $request->coupon)->first() : null;
        if ($promo && isset($data['course'])) {
            $anyScheduleValid = false;
            
            if (isset($data['schedules'])) {
                foreach ($data['schedules'] as &$schedule) {
                    $schedule['is_coupon_valid'] = false;
                    $schedule['discount_value'] = 0;
                    $schedule['discount_type'] = null;
                    $schedule['original_price'] = $data['course']['original_price'];
                    $schedule['price'] = $data['course']['original_price'];

                    $isValid = true;
                    if ($promo->type === 'Specific') {
                        if ($promo->course_id && $promo->course_id != $data['course']['id']) {
                            $isValid = false;
                        }
                        if ($promo->venue_id && $promo->venue_id != $schedule['venue_id']) {
                            $isValid = false;
                        }
                        if ($promo->date && $promo->date != $schedule['start_date']) {
                            $isValid = false;
                        }
                        if (!$promo->course_id && (!$promo->venue_id) && (!$promo->date)) {
                            $isValid = false;
                        }
                    }

                    if ($isValid && $schedule['original_price'] > 0) {
                        $schedule['is_coupon_valid'] = true;
                        $schedule['discount_value'] = $promo->discount_value;
                        $schedule['discount_type'] = $promo->discount_type;
                        
                        if ($promo->discount_type === 'Percentage') {
                            $schedule['price'] = $schedule['original_price'] - ($schedule['original_price'] * ($promo->discount_value / 100));
                        } else {
                            $schedule['price'] = max(0, $schedule['original_price'] - $promo->discount_value);
                        }
                        $anyScheduleValid = true;
                    }
                }
            }

            // For the overall course object, we set it to valid if AT LEAST ONE schedule is valid,
            // or if it's valid for the course itself (e.g. no venue/date constraints)
            $courseValid = true;
            if ($promo->type === 'Specific') {
                if ($promo->course_id && $promo->course_id != $data['course']['id']) {
                    $courseValid = false;
                }
                // If the coupon has venue or date restrictions, the course itself is only "globally" valid 
                // if we don't strictly require the frontend to pick a schedule. 
                // But since schedules are required, we just map it if ANY schedule matched.
                if ($promo->venue_id || $promo->date) {
                    $courseValid = $anyScheduleValid;
                }
            }

            if ($courseValid && $data['course']['original_price'] > 0) {
                $data['course']['is_coupon_valid'] = true;
                $data['course']['discount_value'] = $promo->discount_value;
                $data['course']['discount_type'] = $promo->discount_type;
                
                if ($promo->discount_type === 'Percentage') {
                    $data['course']['price'] = $data['course']['original_price'] - ($data['course']['original_price'] * ($promo->discount_value / 100));
                } else {
                    $data['course']['price'] = max(0, $data['course']['original_price'] - $promo->discount_value);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
