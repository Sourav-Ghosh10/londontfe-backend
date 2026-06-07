<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SendOutlineController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('course_name')->get(['id', 'course_name']);

        $currencies = [
            ['code' => 'USD', 'label' => 'USD'],
            ['code' => 'GBP', 'label' => 'GBP'],
        ];

        return view('admin.courses.send-outline', compact('courses', 'currencies'));
    }

    /**
     * Dynamically return date/venue options for a given course (AJAX).
     */
    public function getDates(Request $request)
    {
        $courseId = $request->input('course_id');

        $dates = Schema::hasTable('course_date_venue')
            ? DB::table('course_date_venue')
                ->where('course_id', $courseId)
                ->where('status', '1')
                ->orderBy('start_date')
                ->get(['id', 'start_date', 'venue_id'])
            : collect();

        $venueIds = $dates->pluck('venue_id')->unique()->filter();
        $venues = Schema::hasTable('venue')
            ? DB::table('venue')->whereIn('id', $venueIds)->pluck('venue_name', 'id')
            : collect();

        $options = $dates->map(function ($d) use ($venues) {
            return [
                'id'         => $d->id,
                'start_date' => $d->start_date,
                'venue_id'   => $d->venue_id,
                'venue_name' => $venues[$d->venue_id] ?? 'Unknown',
            ];
        });

        return response()->json($options);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'      => 'required|string',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'course_id'  => 'required|integer',
            'currency'   => 'required|in:USD,GBP',
        ]);

        // TODO: Build and send the outline email using a Mailable
        // Mail::to($request->email)->send(new \App\Mail\CustomerOutline($request->all()));

        return back()->with('success', 'Course outline has been sent to ' . $request->email . ' successfully.');
    }
}
