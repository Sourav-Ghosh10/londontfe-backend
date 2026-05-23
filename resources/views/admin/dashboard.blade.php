@extends('admin.layout')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">Dashboard</h1>
        <button class="bg-[#008060] hover:bg-[#006e52] text-white text-sm font-medium py-1.5 px-4 rounded-md shadow-sm transition-colors">
            Add Course
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-5 transition-colors duration-200">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 transition-colors">Total Revenue</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white transition-colors">£24,500</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-5 transition-colors duration-200">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 transition-colors">Active Courses</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white transition-colors">42</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-5 transition-colors duration-200">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 transition-colors">New Enrollments</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white transition-colors">1,204</p>
        </div>
    </div>

    <!-- Main Card (Shopify Style) -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-colors duration-200">
        <div class="px-5 py-4 border-b border-gray-300 dark:border-gray-700 flex justify-between items-center transition-colors">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Recent Enrollments</h2>
            <button class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors">View all</button>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 uppercase border-b border-gray-300 dark:border-gray-700 transition-colors">
                    <tr>
                        <th class="px-5 py-3 font-medium">Student</th>
                        <th class="px-5 py-3 font-medium">Course</th>
                        <th class="px-5 py-3 font-medium">Date</th>
                        <th class="px-5 py-3 font-medium text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">John Doe</td>
                        <td class="px-5 py-3 text-gray-700 dark:text-gray-300">AWS Solutions Architect</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">Today at 10:23 AM</td>
                        <td class="px-5 py-3 text-right"><span class="bg-[#e4f8ec] dark:bg-[#008060]/20 text-[#008060] dark:text-[#10b981] text-xs font-semibold px-2 py-0.5 rounded-full transition-colors">Paid</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">Jane Smith</td>
                        <td class="px-5 py-3 text-gray-700 dark:text-gray-300">Advanced Leadership</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">Yesterday</td>
                        <td class="px-5 py-3 text-right"><span class="bg-[#ffebcc] dark:bg-[#f59e0b]/20 text-[#8a6116] dark:text-[#fcd34d] text-xs font-semibold px-2 py-0.5 rounded-full transition-colors">Pending</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">Michael Scott</td>
                        <td class="px-5 py-3 text-gray-700 dark:text-gray-300">Project Management Pro</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">May 21</td>
                        <td class="px-5 py-3 text-right"><span class="bg-[#e4f8ec] dark:bg-[#008060]/20 text-[#008060] dark:text-[#10b981] text-xs font-semibold px-2 py-0.5 rounded-full transition-colors">Paid</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
