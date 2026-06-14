@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/courses"
            class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Popular Courses</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Select and manage courses featured as popular on the website</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-colors">

        <!-- Card Header -->
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Popular Courses Management</h2>
            </div>
            <span id="selected-count-badge"
                class="text-sm font-medium text-gray-500 dark:text-gray-400">
                <span id="selected-count">2</span> selected
            </span>
        </div>

        <!-- Search -->
        <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" />
                    </svg>
                </span>
                <input type="text" id="course-search" placeholder="Search courses..."
                    oninput="filterCourses(this.value)"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
        </div>

        <!-- Select All Row -->
        <div class="px-5 py-2.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex items-center gap-3">
            <input type="checkbox" id="select-all-popular"
                onchange="toggleSelectAll(this)"
                class="rounded border-gray-300 dark:border-gray-600 text-yellow-500 focus:ring-yellow-400 w-4 h-4 cursor-pointer">
            <label for="select-all-popular" class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                Select / Deselect All
            </label>
        </div>

        <!-- Course List -->
        <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[460px] overflow-y-auto" id="course-list">

            @foreach($courses as $course)
            <div class="course-item group flex items-center gap-3 px-5 py-3 transition-colors {{ $course->is_featured == 'yes' ? 'bg-yellow-50 dark:bg-yellow-900/10' : 'hover:bg-gray-50 dark:hover:bg-gray-700/40' }}"
                id="course-row-{{ $course->id }}"
                data-name="{{ strtolower($course->course_name) }}">
                <input type="checkbox"
                    id="course-{{ $course->id }}"
                    value="{{ $course->id }}"
                    {{ $course->is_featured == 'yes' ? 'checked' : '' }}
                    onchange="handleCourseToggle(this, {{ $course->id }})"
                    class="course-checkbox rounded border-gray-300 dark:border-gray-600 text-yellow-500 focus:ring-yellow-400 w-4 h-4 flex-shrink-0 cursor-pointer">
                <label for="course-{{ $course->id }}" class="flex-1 flex items-center gap-2.5 cursor-pointer select-none min-w-0">
                    <span class="text-sm text-gray-800 dark:text-gray-200 course-name truncate">{{ $course->course_name }}</span>
                    <span class="popular-badge {{ $course->is_featured == 'yes' ? '' : 'hidden' }} flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700/50">
                        Popular
                    </span>
                </label>
                <a href="/admin/courses/{{ $course->id }}/edit"
                    class="flex-shrink-0 flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150 ml-2 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Edit Course
                </a>
            </div>
            @endforeach

        </div>

        <!-- Footer Actions -->
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex items-center gap-3">
            <button onclick="savePopularCourses()"
                class="flex items-center gap-2 text-sm font-medium text-white bg-[#4a4a2f] hover:bg-[#3a3a22] dark:bg-yellow-700 dark:hover:bg-yellow-600 px-4 py-2 rounded-md transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save Popular Courses
            </button>
            <button onclick="clearAllPopular()"
                class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear All
            </button>
            <!-- Save feedback -->
            <span id="save-feedback" class="hidden text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Saved successfully!
            </span>
        </div>

    </div>
</div>

<script>
    // Update selected count badge
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.course-checkbox:checked').length;
        document.getElementById('selected-count').textContent = checked;

        // Sync select-all state
        const all = document.querySelectorAll('.course-checkbox').length;
        const selectAll = document.getElementById('select-all-popular');
        selectAll.checked = checked === all;
        selectAll.indeterminate = checked > 0 && checked < all;
    }

    // Handle individual course toggle — update row highlight and badge visibility
    function handleCourseToggle(checkbox, index) {
        const row = document.getElementById('course-row-' + index);
        const badge = row.querySelector('.popular-badge');

        if (checkbox.checked) {
            row.classList.add('bg-yellow-50', 'dark:bg-yellow-900/10');
            row.classList.remove('hover:bg-gray-50', 'dark:hover:bg-gray-700/40');
            badge.classList.remove('hidden');
        } else {
            row.classList.remove('bg-yellow-50', 'dark:bg-yellow-900/10');
            row.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700/40');
            badge.classList.add('hidden');
        }
        updateSelectedCount();
    }

    // Toggle Select / Deselect All (only on visible rows)
    function toggleSelectAll(selectAllCheckbox) {
        const visibleCheckboxes = document.querySelectorAll('.course-item:not([style*="display: none"]) .course-checkbox');
        visibleCheckboxes.forEach((cb, i) => {
            const row = cb.closest('.course-item');
            const index = row.id.replace('course-row-', '');
            cb.checked = selectAllCheckbox.checked;
            handleCourseToggle(cb, index);
        });
        updateSelectedCount();
    }

    // Filter courses by search query
    function filterCourses(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.course-item').forEach(row => {
            const name = row.getAttribute('data-name') || '';
            row.style.display = name.includes(q) ? '' : 'none';
        });
    }

    // Save action
    async function savePopularCourses() {
        const selected = [];
        document.querySelectorAll('.course-checkbox:checked').forEach(cb => {
            selected.push(cb.value);
        });

        const btn = document.querySelector('button[onclick="savePopularCourses()"]');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        try {
            const response = await fetch('/admin/courses/popular', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ popular_courses: selected })
            });

            const data = await response.json();
            if (data.success) {
                const feedback = document.getElementById('save-feedback');
                feedback.classList.remove('hidden');
                setTimeout(() => feedback.classList.add('hidden'), 3000);
            }
        } catch (error) {
            console.error('Error saving popular courses:', error);
            alert('Failed to save. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg> Save Popular Courses`;
        }
    }

    // Clear All
    function clearAllPopular() {
        document.querySelectorAll('.course-checkbox').forEach((cb, i) => {
            cb.checked = false;
            const row = cb.closest('.course-item');
            const index = row.id.replace('course-row-', '');
            handleCourseToggle(cb, index);
        });
        document.getElementById('select-all-popular').checked = false;
        document.getElementById('select-all-popular').indeterminate = false;
        updateSelectedCount();
    }

    // Init count on load
    document.addEventListener('DOMContentLoaded', updateSelectedCount);
</script>
@endsection
