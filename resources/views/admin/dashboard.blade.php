@extends('admin.layout')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Dashboard</h1>
        <a href="/admin/courses/create" class="bg-[#008060] hover:bg-[#006e52] text-white text-sm font-medium py-1.5 px-4 rounded-md shadow-sm transition-colors">
            Add Course
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

        {{-- Total Tiers --}}
        <a href="/admin/course-price/tiers"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-[#008060]/30 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Tiers</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-[#008060] group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-[#008060] transition-colors">8</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-[#008060]/70 transition-colors">View Tiers &rarr;</span>
        </a>

        {{-- Total Courses --}}
        <a href="/admin/courses"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-yellow-300/50 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Courses</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 group-hover:bg-yellow-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-yellow-600 transition-colors">42</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-yellow-500/80 transition-colors">View Courses &rarr;</span>
        </a>

        {{-- Total Blogs --}}
        <a href="/admin/blog"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-purple-300/50 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Blogs</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-purple-50 dark:bg-purple-900/20 text-purple-600 group-hover:bg-purple-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-purple-600 transition-colors">128</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-purple-500/80 transition-colors">View Articles &rarr;</span>
        </a>

        {{-- New Enrollments --}}
        <a href="/admin/logs/quick-enquiry"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-sky-300/50 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">New Enrollments</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-sky-50 dark:bg-sky-900/20 text-sky-600 group-hover:bg-sky-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-sky-600 transition-colors">1,204</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-sky-500/80 transition-colors">View Logs &rarr;</span>
        </a>

        {{-- Active Venues --}}
        <a href="/admin/courses/venues"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-blue-300/50 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Active Venues</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 group-hover:bg-blue-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">18</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-blue-500/80 transition-colors">View Venues &rarr;</span>
        </a>

        {{-- Total Users --}}
        <a href="/admin/users"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-rose-300/50 group flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Users</span>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-rose-50 dark:bg-rose-900/20 text-rose-600 group-hover:bg-rose-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-rose-600 transition-colors">14</p>
            <span class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-rose-500/80 transition-colors">View Users &rarr;</span>
        </a>



    </div>

</div>
@endsection
