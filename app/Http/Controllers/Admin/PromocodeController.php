<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Promocode;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromocodeController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', '1')->orderBy('course_name')->get();
        $venues = Venue::where('status', '1')->orderBy('venue_name')->get();
        
        $promocodesRaw = Promocode::orderBy('id', 'desc')->get();
        
        $promocodes = [];
        foreach ($promocodesRaw as $promo) {
            $course = Course::find($promo->course_id);
            $venue = Venue::find($promo->venue_id);
            
            $promocodes[] = [
                'id' => $promo->id,
                'code' => $promo->code,
                'course' => $course ? $course->course_name : '',
                'date' => $promo->date ? $promo->date : '0000-00-00',
                'venue' => $venue ? $venue->venue_name : '',
                'type' => $promo->discount_type,
                'value' => $promo->discount_value,
                'maxUsage' => $promo->max_usage,
                'used' => $promo->used_usage,
                'status' => $promo->status == 1 ? 'Active' : 'Inactive'
            ];
        }

        return view('admin.courses.promocodes', compact('courses', 'venues', 'promocodes'));
    }

    public function courseDetails(Request $request)
    {
        $courseName = $request->query('course');
        $course = Course::where('course_name', $courseName)->first();
        
        if (!$course) {
            return response()->json(['schedules' => []]);
        }
        
        $schedules = DB::table('course_date_venue')
            ->where('course_id', $course->id)
            ->where('status', '1')
            ->get(['start_date as date', 'venue']);
            
        return response()->json(['schedules' => $schedules]);
    }

    public function store(Request $request)
    {
        $course = Course::where('course_name', $request->course)->first();
        $venue = Venue::where('venue_name', $request->venue)->first();
        
        $promoType = ($course || $venue) ? 'Specific' : 'General';

        $promo = Promocode::create([
            'code' => $request->code,
            'type' => $promoType,
            'course_id' => $course ? $course->id : null,
            'venue_id' => $venue ? $venue->id : null,
            'date' => $request->date !== '0000-00-00' ? $request->date : null,
            'discount_type' => $request->type,
            'discount_value' => $request->value,
            'max_usage' => $request->maxUsage,
            'used_usage' => 0,
            'status' => 1
        ]);

        return response()->json(['success' => true, 'id' => $promo->id]);
    }

    public function update(Request $request, $id)
    {
        $promo = Promocode::findOrFail($id);
        
        $course = Course::where('course_name', $request->course)->first();
        $venue = Venue::where('venue_name', $request->venue)->first();
        
        $promoType = ($course || $venue) ? 'Specific' : 'General';

        $promo->update([
            'code' => $request->code,
            'type' => $promoType,
            'course_id' => $course ? $course->id : null,
            'venue_id' => $venue ? $venue->id : null,
            'date' => $request->date !== '0000-00-00' ? $request->date : null,
            'discount_type' => $request->type,
            'discount_value' => $request->value,
            'max_usage' => $request->maxUsage,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Promocode::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
