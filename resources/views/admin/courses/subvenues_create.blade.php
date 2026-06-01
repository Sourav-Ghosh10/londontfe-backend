@extends('admin.layout')

@section('content')
<div class="w-full pb-12">

    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/courses/subvenues" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Sub Venue</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new specific training center, hotel, or hall associated with a parent global venue city</p>
        </div>
    </div>

    <!-- Main Form -->
    <form onsubmit="handleFormSubmit(event)" id="subvenue-form">
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- ── LEFT COLUMN: Details ─────────────────────── -->
            <div class="flex-1 space-y-6">

                <!-- 1. General Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Sub Venue Information
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Sub Venue Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Sub Venue Name <span class="text-red-500">*</span></label>
                            <input type="text" id="subvenue-name" required placeholder="e.g. London TFE Training Center" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <!-- Parent Primary Venue -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Parent Venue City <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="subvenue-parent" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                    <option value="">Select Parent Venue</option>
                                    <option value="London">London</option>
                                    <option value="Dubai">Dubai</option>
                                    <option value="Vienna">Vienna</option>
                                    <option value="Athens">Athens</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Address / Location details <span class="text-red-500">*</span></label>
                            <input type="text" id="subvenue-address" required placeholder="e.g. Kensington High St, London W8" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── RIGHT COLUMN: Publish Sidebar ────────────────────────────── -->
            <div class="xl:w-80 space-y-6 flex-shrink-0">

                <!-- Actions Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Publish Action</h2>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <!-- Active Status dropdown -->
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Active Status *</label>
                            <select id="subvenue-status" required class="w-full text-xs bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] py-2.5 px-4 rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save
                        </button>
                        <button type="button" onclick="submitAndReturn()" class="w-full flex items-center justify-center gap-2 text-sm font-medium text-[#008060] bg-emerald-55 dark:bg-emerald-900/20 border border-[#008060] py-2.5 px-4 rounded-md hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors">
                            Save and go back to list
                        </button>
                        <a href="/admin/courses/subvenues" class="w-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-750 dark:hover:text-gray-200 py-2 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
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

<!-- ================= JAVASCRIPT ================= -->
<script>
    // Submit handler (Mock API action)
    function handleFormSubmit(e) {
        e.preventDefault();
        saveSubVenueData(false);
    }

    // Save and return to list
    function submitAndReturn() {
        // Validate required fields manually since button is type="button"
        const name = document.getElementById("subvenue-name");
        const parent = document.getElementById("subvenue-parent");
        const address = document.getElementById("subvenue-address");

        if (!name.checkValidity()) {
            name.reportValidity();
            return;
        }
        if (!parent.checkValidity()) {
            parent.reportValidity();
            return;
        }
        if (!address.checkValidity()) {
            address.reportValidity();
            return;
        }

        saveSubVenueData(true);
    }

    function saveSubVenueData(shouldRedirect) {
        const subvenueName = document.getElementById("subvenue-name").value;
        showToast(`Sub venue "${subvenueName}" created successfully!`, "success");

        if (shouldRedirect) {
            setTimeout(() => {
                window.location.href = "/admin/courses/subvenues";
            }, 1200);
        }
    }

    // Toast Control
    function showToast(message, type = "success") {
        const toast = document.getElementById("toast");
        const toastMsg = document.getElementById("toast-message");
        const toastIconWrapper = document.getElementById("toast-icon-wrapper");

        toastMsg.textContent = message;

        if (type === "success") {
            toastIconWrapper.className = "rounded-full p-1 bg-green-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            `;
        }

        toast.classList.remove("translate-y-24", "opacity-0");
        toast.classList.add("translate-y-0", "opacity-100");

        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
            toast.classList.remove("translate-y-0", "opacity-100");
        }, 3000);
    }
</script>
@endsection
