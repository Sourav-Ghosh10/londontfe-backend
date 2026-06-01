@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header & Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-gray-450 dark:text-gray-450">Course</span>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Promotion Code</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Promotion Code Management</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Configure course-specific, venue-specific, and global promotional codes.</p>
        </div>
        <div>
            <button onclick="openAddModal()" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] cursor-pointer whitespace-nowrap">
                + Add
            </button>
        </div>
    </div>

    <!-- Filters and Actions Toolbar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6 transition-all duration-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            
            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Discount Type</label>
                <div class="relative">
                    <select id="type-filter" onchange="filterPromoCodes()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                        <option value="all">All Types</option>
                        <option value="Percentage">Percentage</option>
                        <option value="Fixed">Fixed Amount</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search Codes</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4.5 w-4.5 text-gray-450 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="promo-search" oninput="filterPromoCodes()" placeholder="Search by code, account, course or venue..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
            </div>

        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden transition-all duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xxs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700 transition-colors">
                    <tr>
                        <th class="px-5 py-4">Promotion Code</th>
                        <th class="px-5 py-4">Course</th>
                        <th class="px-5 py-4">Course Date</th>
                        <th class="px-5 py-4">Venue</th>
                        <th class="px-5 py-4">Discount Type</th>
                        <th class="px-5 py-4">Discount Value</th>
                        <th class="px-5 py-4">Maximum Usage</th>
                        <th class="px-5 py-4">Used Usage</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody id="promo-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    <!-- Dynamic Rows Loaded via JS -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden py-16 text-center">
            <div class="p-3 bg-gray-50 dark:bg-gray-750 inline-flex rounded-full text-gray-400 dark:text-gray-500 mb-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No promotion codes found</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try refining your search text or type filters.</p>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="table-summary">
                Showing <span class="font-bold text-gray-900 dark:text-white" id="displayed-start">0</span> to <span class="font-bold text-gray-900 dark:text-white" id="displayed-end">0</span> of <span class="font-bold text-gray-900 dark:text-white" id="total-count">0</span> entries
            </p>
            <nav class="inline-flex items-center gap-1.5" id="pagination-controls" aria-label="Pagination">
                <!-- Dynamic pagination buttons here -->
            </nav>
        </div>
    </div>

</div>

<!-- ================= ADD PROMOCODE MODAL (STACKED FORM LAYOUT) ================= -->
<div id="add-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="add-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeAddModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800/80">
                <h3 class="text-base font-bold text-gray-900 dark:text-white" id="add-modal-title">
                    Add Promocode
                </h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form onsubmit="handleAddPromo(event)" class="p-6 space-y-4">
                
                <!-- Promo Type -->
                <div class="space-y-1.5">
                    <label for="add-type" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Promo type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="add-type" onchange="updateAddInputLabel(this.value)" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                            <option value="Percentage">Percentage</option>
                            <option value="Fixed">Fixed Amount</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>



                <!-- Course -->
                <div class="space-y-1.5">
                    <label for="add-course" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Course</label>
                    <div class="relative">
                        <select id="add-course" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                            <option value="">--Select Course--</option>
                            <option value="Certified Contract Manager and Project Coordinator">Certified Contract Manager and Project Coordinator</option>
                            <option value="Financial Accounts and Reports Training (2 Weeks)">Financial Accounts and Reports Training (2 Weeks)</option>
                            <option value="A-Z of Credit Control">A-Z of Credit Control</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Venue date & Venue Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="add-date" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Venue date</label>
                        <div class="relative">
                            <select id="add-date" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                <option value="">--Select Date--</option>
                                <option value="2025-08-11">2025-08-11</option>
                                <option value="2026-05-25">2026-05-25</option>
                                <option value="2026-06-01">2026-06-01</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="add-venue" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Venue</label>
                        <div class="relative">
                            <select id="add-venue" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                <option value="">--Select Venue--</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="London">London</option>
                                <option value="Dubai">Dubai</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="space-y-1.5">
                    <label for="add-code" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Promo Code <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" id="add-code" required class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors uppercase font-mono" placeholder="e.g. WELCOME10">
                        <button type="button" onclick="generateRandomCode('add-code')" class="px-4 py-2 border border-gray-300 dark:border-gray-650 text-xs font-semibold rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors cursor-pointer">
                            Generate
                        </button>
                    </div>
                    <div id="err-add-code" class="hidden text-xxs text-red-500 font-semibold mt-1">This Promo Code already exists.</div>
                </div>

                <!-- Discount Value & Maximum Usage Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="add-value" id="add-value-label" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Discount Value (%) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="add-value" min="0" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="0">
                            <span id="add-value-suffix" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-gray-500 pointer-events-none">%</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="add-max" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Maximum Usage <span class="text-red-500">*</span></label>
                        <input type="number" id="add-max" min="1" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="100">
                    </div>
                </div>

                <!-- Actions buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 border border-gray-300 dark:border-gray-650 text-sm font-semibold rounded-md text-gray-700 dark:text-gray-250 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] cursor-pointer">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT PROMOCODE MODAL (STACKED FORM LAYOUT) ================= -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800/80">
                <h3 class="text-base font-bold text-gray-900 dark:text-white" id="edit-modal-title">
                    Edit Promocode
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form onsubmit="handleEditPromo(event)" class="p-6 space-y-4">
                <input type="hidden" id="edit-original-code">
                
                <!-- Promo Type -->
                <div class="space-y-1.5">
                    <label for="edit-type" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Promo type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="edit-type" onchange="updateEditInputLabel(this.value)" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                            <option value="Percentage">Percentage</option>
                            <option value="Fixed">Fixed Amount</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>



                <!-- Course -->
                <div class="space-y-1.5">
                    <label for="edit-course" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Course</label>
                    <div class="relative">
                        <select id="edit-course" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                            <option value="">--Select Course--</option>
                            <option value="Certified Contract Manager and Project Coordinator">Certified Contract Manager and Project Coordinator</option>
                            <option value="Financial Accounts and Reports Training (2 Weeks)">Financial Accounts and Reports Training (2 Weeks)</option>
                            <option value="A-Z of Credit Control">A-Z of Credit Control</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Venue date & Venue Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="edit-date" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Venue date</label>
                        <div class="relative">
                            <select id="edit-date" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                <option value="">--Select Date--</option>
                                <option value="2025-08-11">2025-08-11</option>
                                <option value="2026-05-25">2026-05-25</option>
                                <option value="2026-06-01">2026-06-01</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit-venue" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Venue</label>
                        <div class="relative">
                            <select id="edit-venue" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                <option value="">--Select Venue--</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="London">London</option>
                                <option value="Dubai">Dubai</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-550 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="space-y-1.5">
                    <label for="edit-code" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Promo Code <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-code" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors uppercase font-mono" placeholder="e.g. WELCOME10">
                    <div id="err-edit-code" class="hidden text-xxs text-red-500 font-semibold mt-1">This Promo Code already exists.</div>
                </div>

                <!-- Discount Value & Maximum Usage Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="edit-value" id="edit-value-label" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Discount Value (%) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="edit-value" min="0" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="0">
                            <span id="edit-value-suffix" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-gray-500 pointer-events-none">%</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit-max" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Maximum Usage <span class="text-red-500">*</span></label>
                        <input type="number" id="edit-max" min="1" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="100">
                    </div>
                </div>

                <!-- Actions buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-gray-300 dark:border-gray-650 text-sm font-semibold rounded-md text-gray-700 dark:text-gray-250 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Premium Toast Notifications -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div id="toast-icon-wrapper" class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Action completed successfully!</span>
</div>

<!-- ================= JAVASCRIPT LOGIC ================= -->
<script>
    // Pagination state tracking variables
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredPromoCodes = [];

    // Live in-memory datastore with LocalStorage persistence
    let promoCodes = [
        {
            code: "PROMO10",
            course: "",
            date: "0000-00-00",
            venue: "",
            type: "Percentage",
            value: 10,
            maxUsage: 100,
            usedUsage: 1,
            status: true
        },
        {
            code: "CST172025ZUDW",
            course: "",
            date: "0000-00-00",
            venue: "",
            type: "Percentage",
            value: 15,
            maxUsage: 150,
            usedUsage: 4,
            status: true
        },
        {
            code: "RAM012025YBRT",
            course: "",
            date: "0000-00-00",
            venue: "",
            type: "Percentage",
            value: 12,
            maxUsage: 300,
            usedUsage: 1,
            status: true
        },
        {
            code: "SDB05YXIN",
            course: "",
            date: "0000-00-00",
            venue: "",
            type: "Percentage",
            value: 20,
            maxUsage: 10000,
            usedUsage: 34,
            status: true
        },
        {
            code: "WELCOMEDISCOUNT83",
            course: "Certified Contract Manager and Project Coordinator",
            date: "2025-08-11",
            venue: "Kuala Lumpur",
            type: "Percentage",
            value: 50,
            maxUsage: 5,
            usedUsage: 0,
            status: true
        },
        {
            code: "HYX0384A",
            course: "",
            date: "0000-00-00",
            venue: "",
            type: "Percentage",
            value: 100,
            maxUsage: 5,
            usedUsage: 0,
            status: true
        },
        {
            code: "FINANCIAL100",
            course: "Financial Accounts and Reports Training (2 Weeks)",
            date: "2026-05-25",
            venue: "London",
            type: "Percentage",
            value: 50,
            maxUsage: 100,
            usedUsage: 8,
            status: true
        },
        {
            code: "TEST120",
            course: "A-Z of Credit Control",
            date: "2026-06-01",
            venue: "Dubai",
            type: "Percentage",
            value: 10,
            maxUsage: 20,
            usedUsage: 0,
            status: true
        }
    ];

    // Load saved database on setup
    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem("londontfe_promo_codes");
        if (saved) {
            try {
                promoCodes = JSON.parse(saved);
            } catch (e) {
                console.error("Error parsing local promo storage", e);
            }
        }
        renderTable();
    });

    function saveToLocalStorage() {
        localStorage.setItem("londontfe_promo_codes", JSON.stringify(promoCodes));
    }

    // Render table rows dynamically with dynamic pagination support
    function renderTable(data = promoCodes) {
        filteredPromoCodes = data;

        const tbody = document.getElementById("promo-table-body");
        const emptyState = document.getElementById("empty-state");
        const summary = document.getElementById("table-summary");
        
        tbody.innerHTML = "";

        if (data.length === 0) {
            tbody.classList.add("hidden");
            emptyState.classList.remove("hidden");
            summary.innerHTML = `Showing <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> entries`;
            renderPaginationControls(0);
            return;
        }

        tbody.classList.remove("hidden");
        emptyState.classList.add("hidden");

        const totalItems = data.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        // Keep page index inside strict boundary limits
        if (currentPage > totalPages) {
            currentPage = totalPages;
        }
        if (currentPage < 1) {
            currentPage = 1;
        }

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = Math.min(startIdx + itemsPerPage, totalItems);
        const pageData = data.slice(startIdx, endIdx);

        pageData.forEach(item => {
            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";

            // Status Column Badge
            const statusBadge = item.status 
                ? `<button onclick="togglePromoStatus('${item.code}')" class="mx-auto flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 transition-colors focus:outline-none cursor-pointer" title="Active">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                       </svg>
                   </button>`
                : `<button onclick="togglePromoStatus('${item.code}')" class="mx-auto flex items-center justify-center w-6 h-6 rounded-full bg-red-50 hover:bg-red-100 dark:bg-red-950/30 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 transition-colors focus:outline-none cursor-pointer" title="Inactive">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                       </svg>
                   </button>`;

            // Type Badge
            const typeBadge = item.type === "Percentage"
                ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/20 text-indigo-650 dark:text-indigo-400">Percentage</span>`
                : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-55 dark:bg-emerald-950/20 text-emerald-650 dark:text-emerald-400">Fixed</span>`;

            // Display discount formatted
            const discountFormatted = item.type === "Percentage"
                ? `<span class="font-bold text-gray-900 dark:text-white">${item.value}%</span>`
                : `<span class="font-bold text-gray-900 dark:text-white">&pound;${item.value}</span>`;

            tr.innerHTML = `
                <td class="px-5 py-4 font-mono font-bold text-gray-900 dark:text-white uppercase">${item.code}</td>
                <td class="px-5 py-4 max-w-xs truncate font-medium text-gray-650 dark:text-gray-400" title="${item.course || 'All Courses'}">${item.course || '<span class="text-gray-400 dark:text-gray-600">-</span>'}</td>
                <td class="px-5 py-4 font-medium font-mono text-gray-500">${item.date === "0000-00-00" ? '<span class="text-gray-450 dark:text-gray-600">0000-00-00</span>' : item.date}</td>
                <td class="px-5 py-4 font-semibold text-gray-600 dark:text-gray-400">${item.venue || '<span class="text-gray-400 dark:text-gray-600">-</span>'}</td>
                <td class="px-5 py-4">${typeBadge}</td>
                <td class="px-5 py-4">${discountFormatted}</td>
                <td class="px-5 py-4 font-mono text-gray-500 font-semibold">${item.maxUsage}</td>
                <td class="px-5 py-4 font-mono text-gray-500 font-semibold">${item.usedUsage}</td>
                <td class="px-5 py-4 text-center">${statusBadge}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2.5">
                        <button onclick="openEditModal('${item.code}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Edit Promocode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </button>
                        <button onclick="deletePromoCode('${item.code}')" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Delete Promocode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Summary labels
        summary.innerHTML = `Showing <span class="font-bold text-gray-900 dark:text-white">${startIdx + 1}</span> to <span class="font-bold text-gray-900 dark:text-white">${endIdx}</span> of <span class="font-bold text-gray-900 dark:text-white">${totalItems}</span> entries`;
        renderPaginationControls(totalItems);
    }

    // Render dynamic pagination button controls
    function renderPaginationControls(totalItems) {
        const controls = document.getElementById("pagination-controls");
        controls.innerHTML = "";

        if (totalItems === 0) return;

        const totalPages = Math.ceil(totalItems / itemsPerPage);

        // Previous Button
        const prevBtn = document.createElement("button");
        prevBtn.type = "button";
        prevBtn.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable(filteredPromoCodes);
            }
        };
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `flex items-center justify-center p-2 rounded-md border text-xs font-semibold transition-colors cursor-pointer ${
            currentPage === 1 
                ? "bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 border-gray-200 dark:border-gray-700 cursor-not-allowed" 
                : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"
        }`;
        prevBtn.innerHTML = `
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        `;
        controls.appendChild(prevBtn);

        // Page buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement("button");
            pageBtn.type = "button";
            pageBtn.onclick = () => {
                currentPage = i;
                renderTable(filteredPromoCodes);
            };
            pageBtn.className = `flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold transition-colors cursor-pointer ${
                currentPage === i
                    ? "bg-[#008060] text-white border-[#008060]"
                    : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"
            }`;
            pageBtn.innerText = i;
            controls.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement("button");
        nextBtn.type = "button";
        nextBtn.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable(filteredPromoCodes);
            }
        };
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `flex items-center justify-center p-2 rounded-md border text-xs font-semibold transition-colors cursor-pointer ${
            currentPage === totalPages 
                ? "bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 border-gray-200 dark:border-gray-700 cursor-not-allowed" 
                : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"
        }`;
        nextBtn.innerHTML = `
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        `;
        controls.appendChild(nextBtn);
    }

    // Toggle promocode status
    function togglePromoStatus(code) {
        const item = promoCodes.find(p => p.code === code);
        if (item) {
            item.status = !item.status;
            saveToLocalStorage();
            filterPromoCodes();
            showToast(item.status ? `Promocode '${code}' activated successfully!` : `Promocode '${code}' deactivated!`);
        }
    }

    // Delete promocode
    function deletePromoCode(code) {
        if (confirm(`Are you sure you want to delete the promotion code '${code}'?`)) {
            promoCodes = promoCodes.filter(p => p.code !== code);
            saveToLocalStorage();
            filterPromoCodes();
            showToast(`Promocode '${code}' deleted successfully!`);
        }
    }

    // Real-time filtering
    function filterPromoCodes() {
        const filterVal = document.getElementById("type-filter").value;
        const searchVal = document.getElementById("promo-search").value.toLowerCase().trim();

        // Always reset pagination page index to 1 on filter trigger
        currentPage = 1;

        let filtered = promoCodes;

        if (filterVal !== "all") {
            filtered = filtered.filter(p => p.type === filterVal);
        }

        if (searchVal) {
            filtered = filtered.filter(p => 
                p.code.toLowerCase().includes(searchVal) ||
                (p.course && p.course.toLowerCase().includes(searchVal)) ||
                (p.venue && p.venue.toLowerCase().includes(searchVal))
            );
        }

        renderTable(filtered);
    }

    // Open/Close Add Modal
    function openAddModal() {
        document.getElementById("add-modal").classList.remove("hidden");
        document.getElementById("add-code").value = "";
        document.getElementById("add-course").value = "";
        document.getElementById("add-date").value = "";
        document.getElementById("add-venue").value = "";
        document.getElementById("add-type").value = "Percentage";
        document.getElementById("add-value").value = "";
        document.getElementById("add-max").value = "";
        updateAddInputLabel("Percentage");
        document.getElementById("err-add-code").classList.add("hidden");
    }

    function closeAddModal() {
        document.getElementById("add-modal").classList.add("hidden");
    }

    function updateAddInputLabel(type) {
        const label = document.getElementById("add-value-label");
        const suffix = document.getElementById("add-value-suffix");
        if (type === "Percentage") {
            label.innerHTML = `Discount Value (%) <span class="text-red-500">*</span>`;
            suffix.innerText = "%";
        } else {
            label.innerHTML = `Discount Value (&pound;) <span class="text-red-500">*</span>`;
            suffix.innerHTML = "&pound;";
        }
    }

    // Open/Close Edit Modal
    function openEditModal(code) {
        const item = promoCodes.find(p => p.code === code);
        if (!item) return;

        document.getElementById("edit-modal").classList.remove("hidden");
        document.getElementById("edit-original-code").value = code;
        document.getElementById("edit-code").value = item.code;
        document.getElementById("edit-course").value = item.course;
        document.getElementById("edit-date").value = item.date === "0000-00-00" ? "" : item.date;
        document.getElementById("edit-venue").value = item.venue;
        document.getElementById("edit-type").value = item.type;
        document.getElementById("edit-value").value = item.value;
        document.getElementById("edit-max").value = item.maxUsage;
        
        updateEditInputLabel(item.type);
        document.getElementById("err-edit-code").classList.add("hidden");
    }

    function closeEditModal() {
        document.getElementById("edit-modal").classList.add("hidden");
    }

    function updateEditInputLabel(type) {
        const label = document.getElementById("edit-value-label");
        const suffix = document.getElementById("edit-value-suffix");
        if (type === "Percentage") {
            label.innerHTML = `Discount Value (%) <span class="text-red-500">*</span>`;
            suffix.innerText = "%";
        } else {
            label.innerHTML = `Discount Value (&pound;) <span class="text-red-500">*</span>`;
            suffix.innerHTML = "&pound;";
        }
    }

    // Random code generator
    function generateRandomCode(inputId) {
        const prefix = "LondonTFE";
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        let randStr = "";
        for (let i = 0; i < 8; i++) {
            randStr += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(inputId).value = `${prefix}-${randStr}`;
    }

    // Handle adding promo code
    function handleAddPromo(event) {
        event.preventDefault();
        const codeVal = document.getElementById("add-code").value.trim();
        
        // Code existence check (case-insensitive duplicate check)
        const exists = promoCodes.some(p => p.code.toUpperCase() === codeVal.toUpperCase());
        if (exists) {
            document.getElementById("err-add-code").classList.remove("hidden");
            return;
        }

        const newPromo = {
            code: codeVal,
            course: document.getElementById("add-course").value,
            date: document.getElementById("add-date").value || "0000-00-00",
            venue: document.getElementById("add-venue").value,
            type: document.getElementById("add-type").value,
            value: parseInt(document.getElementById("add-value").value) || 0,
            maxUsage: parseInt(document.getElementById("add-max").value) || 100,
            usedUsage: 0,
            status: true
        };

        promoCodes.unshift(newPromo);
        saveToLocalStorage();
        closeAddModal();
        filterPromoCodes();
        showToast(`Promocode '${codeVal}' added successfully!`);
    }

    // Handle editing promo code
    function handleEditPromo(event) {
        event.preventDefault();
        const originalCode = document.getElementById("edit-original-code").value;
        const codeVal = document.getElementById("edit-code").value.trim();

        // Existence check (excluding ourselves, case-insensitive)
        if (originalCode.toUpperCase() !== codeVal.toUpperCase()) {
            const exists = promoCodes.some(p => p.code.toUpperCase() === codeVal.toUpperCase());
            if (exists) {
                document.getElementById("err-edit-code").classList.remove("hidden");
                return;
            }
        }

        const index = promoCodes.findIndex(p => p.code === originalCode);
        if (index !== -1) {
            promoCodes[index].code = codeVal;
            promoCodes[index].course = document.getElementById("edit-course").value;
            promoCodes[index].date = document.getElementById("edit-date").value || "0000-00-00";
            promoCodes[index].venue = document.getElementById("edit-venue").value;
            promoCodes[index].type = document.getElementById("edit-type").value;
            promoCodes[index].value = parseInt(document.getElementById("edit-value").value) || 0;
            promoCodes[index].maxUsage = parseInt(document.getElementById("edit-max").value) || 100;
            
            saveToLocalStorage();
            closeEditModal();
            filterPromoCodes();
            showToast(`Promocode '${codeVal}' updated successfully!`);
        }
    }

    // Show dynamic toast alert
    function showToast(message) {
        const toast = document.getElementById("toast");
        const msg = document.getElementById("toast-message");
        msg.innerText = message;
        
        toast.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        
        setTimeout(() => {
            toast.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        }, 3500);
    }
</script>
@endsection
