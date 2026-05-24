@extends('admin.layout')

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
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-5 mb-5 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-start">

            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    </span>
                    <input type="text" placeholder="Search course name..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Date Range</label>
                <input type="text" placeholder="MM/DD/YYYY – MM/DD/YYYY" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <!-- Venue Multi-Select -->
            <div class="relative" id="venue-wrapper">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Venue</label>
                <button type="button" onclick="toggleDropdown('venue')"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 rounded-md px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors flex items-center justify-between">
                    <span id="venue-label" class="truncate">All Venues</span>
                    <svg class="w-4 h-4 flex-shrink-0 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <!-- Dropdown Panel -->
                <div id="venue-dropdown" class="hidden absolute z-50 mt-1 w-full min-w-[220px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="text" placeholder="Search venues..." oninput="filterOptions('venue', this.value)"
                            class="w-full text-xs bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="max-h-48 overflow-y-auto py-1" id="venue-options">
                        @foreach(['035','Abu Dhabi','Amman','Amsterdam','Athens','Atlanta','Auckland','Bahrain','Bangkok','Barcelona','Beijing','Beirut','Berlin','Brisbane','Brussels','Cairo','Casablanca','Chicago','Copenhagen','Dubai','Frankfurt','Geneva','Hong Kong','Istanbul','Jakarta','Johannesburg','Kuala Lumpur','Kuwait','Lagos','London','Los Angeles','Madrid','Manila','Melbourne','Miami','Milan','Montreal','Moscow','Mumbai','Munich','Nairobi','New York','Oslo','Paris','Perth','Prague','Riyadh','Rome','San Francisco','Seoul','Shanghai','Singapore','Stockholm','Sydney','Taipei','Tokyo','Toronto','Vienna','Warsaw','Washington DC','Zurich'] as $venue)
                        <label class="flex items-center gap-2.5 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer venue-option">
                            <input type="checkbox" value="{{ $venue }}" onchange="updateMultiSelect('venue')"
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 flex-shrink-0">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $venue }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <button type="button" onclick="clearMultiSelect('venue')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear all</button>
                        <button type="button" onclick="toggleDropdown('venue')" class="text-xs font-medium text-white bg-[#008060] hover:bg-[#006e52] px-3 py-1 rounded-md transition-colors">Done</button>
                    </div>
                </div>
            </div>

            <!-- Category Multi-Select -->
            <div class="relative" id="category-wrapper">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Category</label>
                <button type="button" onclick="toggleDropdown('category')"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 rounded-md px-3 py-2 text-left focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors flex items-center justify-between">
                    <span id="category-label" class="truncate">All Categories</span>
                    <svg class="w-4 h-4 flex-shrink-0 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <!-- Dropdown Panel -->
                <div id="category-dropdown" class="hidden absolute z-50 mt-1 w-full min-w-[240px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="text" placeholder="Search categories..." oninput="filterOptions('category', this.value)"
                            class="w-full text-xs bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="max-h-48 overflow-y-auto py-1" id="category-options">
                        @foreach(['Accounting and Finance','Administration and Office Management','Business Administration','Leadership and Management','Contract and Project Management','Energy and Sustainability','Oil and Gas','Sales and Marketing','Human Resources'] as $cat)
                        <label class="flex items-center gap-2.5 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer category-option">
                            <input type="checkbox" value="{{ $cat }}" onchange="updateMultiSelect('category')"
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 flex-shrink-0">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $cat }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <button type="button" onclick="clearMultiSelect('category')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear all</button>
                        <button type="button" onclick="toggleDropdown('category')" class="text-xs font-medium text-white bg-[#008060] hover:bg-[#006e52] px-3 py-1 rounded-md transition-colors">Done</button>
                    </div>
                </div>
            </div>

            <!-- Status + Clear -->
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                <div class="flex gap-2">
                    <select class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Draft</option>
                    </select>
                    <button class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                        Clear
                    </button>
                </div>
            </div>

        </div>
    </div>

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
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Created</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    @php
                        $courses = [
                            ['name' => 'A-Z of Credit Control', 'category' => 'Accounting and Finance', 'duration' => '5 Days', 'sync' => true, 'rating' => 1, 'status' => 'Active', 'created' => '18-03-2026'],
                            ['name' => 'Oil and Gas Mini MBA, Management and Business Administration', 'category' => 'Oil and Gas', 'duration' => '5 Days', 'sync' => false, 'rating' => 1, 'status' => 'Active', 'created' => '10-03-2026'],
                            ['name' => 'Certified Leadership and Management Excellence CMI Recognised', 'category' => 'Leadership and Management', 'duration' => '5 Days', 'sync' => true, 'rating' => 1, 'status' => 'Active', 'created' => '10-03-2026'],
                            ['name' => 'Certified Cost Manager', 'category' => 'Accounting and Finance', 'duration' => '5 Days', 'sync' => false, 'rating' => 2, 'status' => 'Active', 'created' => '09-03-2026'],
                            ['name' => 'Certified Associate in Project Management CAPM® Exam Preparatory', 'category' => 'Contract and Project Management', 'duration' => '3 Days', 'sync' => true, 'rating' => 3, 'status' => 'Draft', 'created' => '03-03-2026'],
                            ['name' => 'Advanced Leadership Programme', 'category' => 'Leadership and Management', 'duration' => '5 Days', 'sync' => true, 'rating' => 4, 'status' => 'Active', 'created' => '01-03-2026'],
                            ['name' => 'ADR and Oil and Gas Disputes: Exploring Mediation and Arbitration Techniques', 'category' => 'Energy and Sustainability, Oil and Gas', 'duration' => '5 Days', 'sync' => false, 'rating' => 2, 'status' => 'Inactive', 'created' => '05-02-2026'],
                        ];
                    @endphp

                    @foreach($courses as $course)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group cursor-pointer">
                        <td class="px-5 py-3.5">
                            <input type="checkbox" class="row-check rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        </td>
                        <td class="px-5 py-3.5 max-w-xs">
                            <span class="font-medium text-gray-900 dark:text-white line-clamp-2">{{ $course['name'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[160px]">
                            <span class="line-clamp-1">{{ $course['category'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium px-2 py-0.5 rounded-full">{{ $course['duration'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($course['sync'])
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
                                    @if($i <= $course['rating'])
                                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($course['status'] === 'Active')
                                <span class="bg-[#e4f8ec] dark:bg-green-900/30 text-[#008060] dark:text-green-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">Active</span>
                            @elseif($course['status'] === 'Draft')
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-semibold px-2.5 py-0.5 rounded-full">Draft</span>
                            @else
                                <span class="bg-[#ffebcc] dark:bg-yellow-900/30 text-[#8a6116] dark:text-yellow-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                            {{ $course['created'] }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button title="Edit" class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button title="Download PDF" class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </button>
                                <button title="Delete" class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-300 dark:border-gray-700 flex items-center justify-between transition-colors">
            <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium text-gray-900 dark:text-white">1–7</span> of <span class="font-medium text-gray-900 dark:text-white">128</span> courses</p>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1.5 text-sm text-white bg-[#008060] border border-[#008060] rounded-md">1</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">2</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">3</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDropdown(key) {
        document.getElementById(key + '-dropdown').classList.toggle('hidden');
    }

    function updateMultiSelect(key) {
        const checks = document.querySelectorAll(`#${key}-options input[type=checkbox]:checked`);
        const label  = document.getElementById(key + '-label');
        const values = Array.from(checks).map(c => c.value);

        if (values.length === 0) {
            label.textContent = key === 'category' ? 'All Categories' : 'All Venues';
        } else if (values.length === 1) {
            label.textContent = values[0];
        } else {
            label.textContent = `${values.length} selected`;
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

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        ['category', 'venue'].forEach(key => {
            const wrapper = document.getElementById(key + '-wrapper');
            const dd      = document.getElementById(key + '-dropdown');
            if (wrapper && !wrapper.contains(e.target)) dd.classList.add('hidden');
        });
    });

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
</script>
@endsection
