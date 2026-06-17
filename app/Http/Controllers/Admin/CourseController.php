<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseCategoryAssoc;
use App\Models\PriceTier;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->filled('search')) {
            $query->where('course_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('create_date', '>=', \Carbon\Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('create_date', '<=', \Carbon\Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d'));
        }

        if ($request->filled('category')) {
            $categoryIds = array_map('intval', (array) $request->category);
            $matchingCourseIds = \DB::table('course_category_assoc')
                ->whereIn('category_id', $categoryIds)
                ->pluck('course_id')
                ->toArray();
            $query->whereIn('id', $matchingCourseIds);
        }

        if ($request->filled('venue')) {
            $venueIds = array_map('intval', (array) $request->venue);
            $courseIdsFromDateVenue = \DB::table('course_date_venue')
                ->whereIn('venue_id', $venueIds)
                ->pluck('course_id')
                ->toArray();
            $query->whereIn('id', $courseIdsFromDateVenue);
        }

        $filterCategories = CourseCategory::orderBy('category_name')->get();
        $filterVenues = \Illuminate\Support\Facades\Schema::hasTable('venue')
            ? \DB::table('venue')->orderBy('venue_name')->get()
            : collect();
        $courses = $query->orderBy('create_date', 'desc')->paginate(15)->withQueryString();

        return view('admin.courses.index', compact('courses', 'filterCategories', 'filterVenues'));
    }

    public function create()
    {
        $course = new Course();
        $categories = CourseCategory::where('status', 'active')->orderBy('category_name')->get();
        $priceTiers = PriceTier::orderBy('tier_name')->get();
        
        $primaryCategory = null;
        $secondaryCategories = [];
        $selectedAccreditations = [];
        $accreditations = \Illuminate\Support\Facades\Schema::hasTable('accreditation_content') ? \DB::table('accreditation_content')->where('status', '1')->get() : collect();
        $venues = \Illuminate\Support\Facades\Schema::hasTable('venue') ? \DB::table('venue')->where('status', '1')->get() : collect();
        
        return view('admin.courses.create', compact('course', 'categories', 'priceTiers', 'primaryCategory', 'secondaryCategories', 'selectedAccreditations', 'accreditations', 'venues'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $categories = CourseCategory::where('status', 'active')->orderBy('category_name')->get();
        $priceTiers = PriceTier::orderBy('tier_name')->get();

        $primaryCategory = \DB::table('course_category_assoc')
            ->where('course_id', $id)
            ->where('type', 'p')
            ->value('category_id');

        $secondaryCategories = \DB::table('course_category_assoc')
            ->where('course_id', $id)
            ->where('type', 's')
            ->pluck('category_id')
            ->toArray();

        $selectedAccreditations = \Illuminate\Support\Facades\Schema::hasTable('course_accreditation_assoc') ? \DB::table('course_accreditation_assoc')
            ->where('course_id', $id)
            ->pluck('accreditation_id')
            ->toArray() : [];

        $accreditations = \Illuminate\Support\Facades\Schema::hasTable('accreditation_content') ? \DB::table('accreditation_content')->where('status', '1')->get() : collect();
        $venues = \Illuminate\Support\Facades\Schema::hasTable('venue') ? \DB::table('venue')->where('status', '1')->get() : collect();

        $seo = \Illuminate\Support\Facades\Schema::hasTable('seo') ? \App\Models\Seo::where('reference_id', $id)->where('page_type', 'Course')->first() : null;
        if ($seo) {
            $course->seo_title = $seo->title;
            $course->meta_description = $seo->meta_description;
        }

        return view('admin.courses.edit', compact('course', 'categories', 'priceTiers', 'primaryCategory', 'secondaryCategories', 'selectedAccreditations', 'accreditations', 'venues'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', 'primary_category', 'secondary_category', 'course_accreditation', 'seo_title', 'meta_description']);
        
        $data['create_date'] = now();
        $data['last_updated'] = now();

        $course = Course::create($data);

        if ($request->has('primary_category') && $request->primary_category) {
            \DB::table('course_category_assoc')->insert([
                'course_id' => $course->id,
                'category_id' => $request->primary_category,
                'type' => 'p'
            ]);
        }
        
        if ($request->has('secondary_category') && is_array($request->secondary_category)) {
            foreach ($request->secondary_category as $cat) {
                \DB::table('course_category_assoc')->insert([
                    'course_id' => $course->id,
                    'category_id' => $cat,
                    'type' => 's'
                ]);
            }
        }

        if ($request->has('course_accreditation') && is_array($request->course_accreditation)) {
            if (\Illuminate\Support\Facades\Schema::hasTable('course_accreditation_assoc')) {
                foreach ($request->course_accreditation as $acc) {
                    \DB::table('course_accreditation_assoc')->insert([
                        'course_id' => $course->id,
                        'accreditation_id' => $acc
                    ]);
                }
            }
        }

        if ($request->filled('seo_title') || $request->filled('meta_description')) {
            if (\Illuminate\Support\Facades\Schema::hasTable('seo')) {
                \App\Models\Seo::create([
                    'title' => $request->seo_title,
                    'page_type' => 'Course',
                    'reference_id' => $course->id,
                    'meta_description' => $request->meta_description,
                    'status' => $request->status == '1' ? '1' : '0',
                    'create_date' => now(),
                    'last_updated' => now()
                ]);
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $data = $request->except(['_token', '_method', 'primary_category', 'secondary_category', 'course_accreditation', 'seo_title', 'meta_description']);
        $data['last_updated'] = now();

        $course->update($data);

        \DB::table('course_category_assoc')->where('course_id', $id)->delete();
        if ($request->has('primary_category') && $request->primary_category) {
            \DB::table('course_category_assoc')->insert([
                'course_id' => $id,
                'category_id' => $request->primary_category,
                'type' => 'p'
            ]);
        }
        if ($request->has('secondary_category') && is_array($request->secondary_category)) {
            foreach ($request->secondary_category as $cat) {
                \DB::table('course_category_assoc')->insert([
                    'course_id' => $id,
                    'category_id' => $cat,
                    'type' => 's'
                ]);
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('course_accreditation_assoc')) {
            \DB::table('course_accreditation_assoc')->where('course_id', $id)->delete();
            if ($request->has('course_accreditation') && is_array($request->course_accreditation)) {
                foreach ($request->course_accreditation as $acc) {
                    \DB::table('course_accreditation_assoc')->insert([
                        'course_id' => $id,
                        'accreditation_id' => $acc
                    ]);
                }
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('seo')) {
            $seo = \App\Models\Seo::where('reference_id', $id)->where('page_type', 'Course')->first();
            if ($seo) {
                $seo->update([
                    'title' => $request->seo_title,
                    'meta_description' => $request->meta_description,
                    'status' => $request->status == '1' ? '1' : '0',
                    'last_updated' => now()
                ]);
            } elseif ($request->filled('seo_title') || $request->filled('meta_description')) {
                \App\Models\Seo::create([
                    'title' => $request->seo_title,
                    'page_type' => 'Course',
                    'reference_id' => $id,
                    'meta_description' => $request->meta_description,
                    'status' => $request->status == '1' ? '1' : '0',
                    'create_date' => now(),
                    'last_updated' => now()
                ]);
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->categories()->detach();
        $course->delete();
        return response()->json(['success' => true]);
    }
    public function popular()
    {
        $courses = Course::where('course_type', '1')
            ->where('status', '1')
            ->orderByRaw("is_featured = 'yes' DESC")
            ->orderBy('course_name', 'asc')
            ->get(['id', 'course_name', 'is_featured']);
        return view('admin.courses.popular', compact('courses'));
    }

    public function updatePopular(Request $request)
    {
        $selectedCourseIds = $request->input('popular_courses', []);

        // Reset all courses
        Course::query()->update(['is_featured' => 'no']);

        // Set selected courses as featured
        if (!empty($selectedCourseIds)) {
            Course::whereIn('id', $selectedCourseIds)->update(['is_featured' => 'yes']);
        }

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_popular_courses_v1');

        return response()->json(['success' => true]);
    }

    public function togglePopular(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->is_featured = $request->is_featured ? 'yes' : 'no';
        $course->save();

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_popular_courses_v1');

        return response()->json(['success' => true, 'is_featured' => $course->is_featured]);
    }
}
