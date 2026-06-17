@extends('admin.layout')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar { font-size: 12px; }
    .flatpickr-day.inRange { background: #008060; border-color: #008060; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #008060; border-color: #008060; }
    .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover { background: #006e52; border-color: #006e52; }
</style>
@endpush

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Training Courses</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage and publish your training courses</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export
            </button>
            <a href="/admin/courses/create" class="flex items-center gap-1.5 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] px-4 py-2 rounded-md transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Course
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <form id="filter-form" method="GET" action="/admin/courses">
        <button type="submit" class="hidden">Submit</button>
        <!-- Hidden inputs for date range -->
        <input type="hidden" name="date_from" id="filter-date-from" value="{{ request('date_from') }}">
        <input type="hidden" name="date_to" id="filter-date-to" value="{{ request('date_to') }}">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-5 mb-5 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-start">

            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search course name..."
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        oninput="submitFilterDebounced()">
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Date Range</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <input id="date-range-picker" type="text"
                        placeholder="DD-MM-YYYY to DD-MM-YYYY"
                        value="{{ request('date_from') && request('date_to') ? request('date_from').' to '.request('date_to') : '' }}"
                        readonly
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-9 pr-8 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors cursor-pointer">
                    <button type="button" id="date-range-clear" onclick="clearDateRange()" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 {{ request('date_from') ? '' : 'hidden' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Venue Multi-Select -->
            <div class="relative" id="venue-wrapper">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Venue</label>
                <button type="button" onclick="toggleDropdown('venue')"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 rounded-md px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors flex items-center justify-between">
                    <span id="venue-label" class="truncate">{{ count(request()->get('venue', [])) ? count(request()->get('venue', [])).' selected' : 'All Venues' }}</span>
                    <svg class="w-4 h-4 flex-shrink-0 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="venue-dropdown" class="hidden absolute z-50 mt-1 w-full min-w-[220px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="text" placeholder="Search venues..." oninput="filterOptions('venue', this.value)"
                            class="w-full text-xs bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="max-h-48 overflow-y-auto py-1" id="venue-options">
                        @foreach($filterVenues as $venue)
                        <label class="flex items-center gap-2.5 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer venue-option">
                            <input type="checkbox" name="venue[]" value="{{ $venue->id }}"
                                {{ in_array($venue->id, request()->get('venue', [])) ? 'checked' : '' }}
                                onchange="updateMultiSelect('venue')"
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 flex-shrink-0">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $venue->venue_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <button type="button" onclick="clearMultiSelect('venue')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear all</button>
                        <button type="button" onclick="toggleDropdown('venue'); document.getElementById('filter-form').submit()" class="text-xs font-medium text-white bg-[#008060] hover:bg-[#006e52] px-3 py-1 rounded-md transition-colors">Done</button>
                    </div>
                </div>
            </div>

            <!-- Category Multi-Select -->
            <div class="relative" id="category-wrapper">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Category</label>
                <button type="button" onclick="toggleDropdown('category')"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 rounded-md px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors flex items-center justify-between">
                    <span id="category-label" class="truncate">{{ count(request()->get('category', [])) ? count(request()->get('category', [])).' selected' : 'All Categories' }}</span>
                    <svg class="w-4 h-4 flex-shrink-0 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="category-dropdown" class="hidden absolute z-50 mt-1 w-full min-w-[240px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="text" placeholder="Search categories..." oninput="filterOptions('category', this.value)"
                            class="w-full text-xs bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="max-h-48 overflow-y-auto py-1" id="category-options">
                        @foreach($filterCategories as $cat)
                        <label class="flex items-center gap-2.5 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer category-option">
                            <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                                {{ in_array($cat->id, request()->get('category', [])) ? 'checked' : '' }}
                                onchange="updateMultiSelect('category')"
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 flex-shrink-0">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $cat->category_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <button type="button" onclick="clearMultiSelect('category')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear all</button>
                        <button type="button" onclick="toggleDropdown('category'); document.getElementById('filter-form').submit()" class="text-xs font-medium text-white bg-[#008060] hover:bg-[#006e52] px-3 py-1 rounded-md transition-colors">Done</button>
                    </div>
                </div>
            </div>

            <!-- Status + Clear -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                <div class="flex gap-2">
                    <select name="status" onchange="document.getElementById('filter-form').submit()"
                        class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <a href="/admin/courses" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                        Clear
                    </a>
                </div>
            </div>

        </div>
    </div>
    </form>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-colors">
        <!-- Table Toolbar -->
        <div class="px-5 py-3.5 border-b border-gray-300 dark:border-gray-700 flex items-center justify-between transition-colors">
            <div class="flex items-center gap-3">
                <input id="select-all" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-4 h-4">
                <span class="text-sm text-gray-500 dark:text-gray-400" id="selected-label">128 courses</span>
            </div>
            <div id="bulk-actions" class="hidden flex gap-2">
                <button class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Delete selected</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/40 uppercase border-b border-gray-300 dark:border-gray-700 transition-colors">
                    <tr>
                        <th class="px-5 py-3 w-8"></th>
                        <th class="px-5 py-3">Course Name</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3 text-center">Duration</th>
                        <th class="px-5 py-3 text-center">Sync API</th>
                        <th class="px-5 py-3 text-center">Rating</th>
                        <th class="px-5 py-3 text-center">Popular</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Created</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    @forelse($courses as $course)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group cursor-pointer">
                        <td class="px-5 py-3.5">
                            <input type="checkbox" class="row-check rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-4 h-4" value="{{ $course->id }}">
                        </td>
                        <td class="px-5 py-3.5 max-w-xs">
                            <span class="font-medium text-gray-900 dark:text-white line-clamp-2">{{ $course->course_name }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[160px]">
                            <span class="line-clamp-1">
                                {{ $course->categories->pluck('category_name')->implode(', ') ?: 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            @php
                                $durationLabels = ['1' => 'Day(s)', '2' => 'Week(s)', '3' => 'Month(s)'];
                                $durationLabel = $durationLabels[$course->course_duration_type] ?? $course->course_duration_type;
                            @endphp
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium px-2 py-0.5 rounded-full">{{ $course->course_duration }} {{ $durationLabel }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($course->is_publish == 1)
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-400 rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($course->rating ?? 0))
                                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <label class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation()">
                                <input type="checkbox" class="sr-only peer" onchange="togglePopular(this, {{ $course->id }})" {{ $course->is_featured == 'yes' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-[#008060]"></div>
                            </label>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($course->status == 1)
                                <span class="bg-[#e4f8ec] dark:bg-green-900/30 text-[#008060] dark:text-green-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">Active</span>
                            @else
                                <span class="bg-[#ffebcc] dark:bg-yellow-900/30 text-[#8a6116] dark:text-yellow-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                            {{ $course->create_date ? \Carbon\Carbon::parse($course->create_date)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                                <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors" title="More actions">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="1.5"/>
                                        <circle cx="12" cy="12" r="1.5"/>
                                        <circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <div class="kebab-menu hidden absolute right-0 mt-1 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </a>
                                    <button class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/></svg>
                                        Download Outline PDF
                                    </button>
                                    <button class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/></svg>
                                        Download Outline Word
                                    </button>
                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-8 text-center text-sm text-gray-500">No courses found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-300 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
            <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium text-gray-900 dark:text-white">{{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900 dark:text-white">{{ $courses->total() }}</span> courses</p>
            <div class="flex gap-1">
                {{ $courses->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDropdown(key) {
        const dd = document.getElementById(key + '-dropdown');
        const isHidden = dd.classList.contains('hidden');
        dd.classList.toggle('hidden');
        // When opening, move checked items to the top
        if (isHidden) {
            const container = document.getElementById(key + '-options');
            const allItems  = Array.from(container.querySelectorAll('label'));
            const checked   = allItems.filter(l => l.querySelector('input').checked);
            const unchecked = allItems.filter(l => !l.querySelector('input').checked);
            container.innerHTML = '';
            checked.forEach(l => container.appendChild(l));
            if (checked.length > 0 && unchecked.length > 0) {
                const divider = document.createElement('div');
                divider.className = 'border-t border-gray-200 dark:border-gray-600 my-1 mx-3';
                container.appendChild(divider);
            }
            unchecked.forEach(l => container.appendChild(l));
        }
    }

    function updateMultiSelect(key) {
        const checks = document.querySelectorAll(`#${key}-options input[type=checkbox]:checked`);
        const label  = document.getElementById(key + '-label');
        const count  = checks.length;

        if (count === 0) {
            label.textContent = key === 'category' ? 'All Categories' : 'All Venues';
        } else if (count === 1) {
            label.textContent = checks[0].closest('label').querySelector('span').textContent.trim();
        } else {
            label.textContent = `${count} selected`;
        }
    }

    function clearMultiSelect(key) {
        document.querySelectorAll(`#${key}-options input[type=checkbox]`).forEach(c => c.checked = false);
        updateMultiSelect(key);
    }

    function filterOptions(key, query) {
        const q = query.toLowerCase();
        document.querySelectorAll(`#${key}-options .${key}-option`).forEach(opt => {
            const text = opt.querySelector('span').textContent.toLowerCase();
            opt.style.display = text.includes(q) ? '' : 'none';
        });
    }

    // Close filter dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        ['category', 'venue'].forEach(key => {
            const wrapper = document.getElementById(key + '-wrapper');
            const dd      = document.getElementById(key + '-dropdown');
            if (wrapper && !wrapper.contains(e.target)) dd.classList.add('hidden');
        });
        // Close all kebab menus when clicking outside
        if (!e.target.closest('.kebab-menu') && !e.target.closest('[onclick="toggleKebab(this)"]')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    function toggleKebab(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.kebab-menu').forEach(m => {
            m.classList.add('hidden');
            m.style.position = '';
            m.style.top = '';
            m.style.left = '';
        });
        
        if (!isOpen) {
            menu.classList.remove('hidden');
            const rect = btn.getBoundingClientRect();
            menu.style.position = 'fixed';
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.left = (rect.right - 224) + 'px'; // w-56 is 224px
        }
    }

    // Hide dropdown on any scroll event to prevent floating menus
    document.addEventListener('scroll', function(e) {
        if (!e.target.closest('.kebab-menu')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    }, true);

    // Row checkbox logic
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        document.getElementById('bulk-actions').classList.toggle('hidden', !this.checked);
        document.getElementById('selected-label').textContent = this.checked
            ? `${document.querySelectorAll('.row-check').length} selected` : '128 courses';
    });
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.row-check:checked').length;
            document.getElementById('bulk-actions').classList.toggle('hidden', checked === 0);
            document.getElementById('selected-label').textContent = checked > 0 ? `${checked} selected` : '128 courses';
        });
    });

    function togglePopular(checkbox, courseId) {
        const isChecked = checkbox.checked;
        fetch(`/admin/courses/${courseId}/toggle-popular`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_featured: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                checkbox.checked = !isChecked; // revert
                alert('Failed to update popular status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !isChecked; // revert
            alert('An error occurred.');
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Flatpickr date range — populates hidden inputs & submits
    const drClear  = document.getElementById('date-range-clear');
    const drFrom   = document.getElementById('filter-date-from');
    const drTo     = document.getElementById('filter-date-to');

    const drPicker = flatpickr('#date-range-picker', {
        mode: 'range',
        dateFormat: 'd-m-Y',
        allowInput: false,
        disableMobile: true,
        defaultDate: [
            drFrom && drFrom.value ? drFrom.value : null,
            drTo   && drTo.value   ? drTo.value   : null
        ].filter(Boolean),
        onChange: function(selectedDates) {
            drClear.classList.toggle('hidden', selectedDates.length === 0);
            if (selectedDates.length === 2) {
                const fmt = d => d.toLocaleDateString('en-GB').split('/').join('-'); // dd-mm-yyyy
                drFrom.value = fmt(selectedDates[0]);
                drTo.value   = fmt(selectedDates[1]);
                document.getElementById('filter-form').submit();
            }
        }
    });

    function clearDateRange() {
        drPicker.clear();
        drFrom.value = '';
        drTo.value   = '';
        drClear.classList.add('hidden');
        document.getElementById('filter-form').submit();
    }

    // Debounced search
    let searchTimer;
    function submitFilterDebounced() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500);
    }

    // Refocus search input after reload
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });
</script>
@endsection
