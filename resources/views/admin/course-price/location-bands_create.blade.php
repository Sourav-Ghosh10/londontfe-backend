@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-gray-600 dark:text-gray-300">Courses</span>
                <span>&rsaquo;</span>
                <a href="/admin/course-price/location-bands" class="hover:text-gray-600 dark:hover:text-gray-300">Location Band</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Add Location Band</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Location Band</h1>
        </div>
        <a href="/admin/course-price/location-bands" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Location Bands
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Add Location Band</h2>
            </div>

            <form onsubmit="handleSave(event, false)" class="p-6 space-y-5">

                <!-- Flex Row Layout to match screenshot structure better, or stacked? Screenshot shows label left, input right. Let's use grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label for="band-name" class="text-xs font-bold text-gray-700 dark:text-gray-400">
                        Band Name* :
                    </label>
                    <div class="md:col-span-3">
                        <input type="text" id="band-name" required class="w-full text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label for="band-type" class="text-xs font-bold text-gray-700 dark:text-gray-400">
                        Band Type* :
                    </label>
                    <div class="md:col-span-3">
                        <select id="band-type" required class="w-1/2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060]">
                            <option value="">Select Band Type</option>
                            <option value="Plus">Plus</option>
                            <option value="Minus">Minus</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label for="band-venues" class="text-xs font-bold text-gray-700 dark:text-gray-400">
                        Venue(s) :
                    </label>
                    <div class="md:col-span-3">
                        <select id="band-venues" multiple="multiple" class="w-full md:w-1/2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060]">
                            @foreach($venues as $v)
                                <option value="{{ $v->id }}">{{ $v->venue_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label for="band-adj" class="text-xs font-bold text-gray-700 dark:text-gray-400">
                        Adjustment(%)* :
                    </label>
                    <div class="md:col-span-3">
                        <input type="number" id="band-adj" required class="w-1/2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060]">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 rounded transition-colors cursor-pointer">
                        Save
                    </button>
                    <button type="button" onclick="handleSave(event, true)" class="px-5 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 rounded transition-colors cursor-pointer">
                        Save and go back to list
                    </button>
                    <a href="/admin/course-price/location-bands" class="px-5 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 rounded transition-colors cursor-pointer">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Saved!</span>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom styling for Select2 to match Tailwind theme */
    .select2-container--default .select2-selection--multiple {
        border-color: #d1d5db;
        border-radius: 0.375rem;
        min-height: 42px;
        padding-top: 2px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #008060;
        outline: 0;
        box-shadow: 0 0 0 1px #008060;
    }
    .dark .select2-container--default .select2-selection--multiple {
        background-color: #374151;
        border-color: #4b5563;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4b5563;
        border-color: #6b7280;
        color: #f3f4f6;
    }
    .dark .select2-dropdown {
        background-color: #374151;
        border-color: #4b5563;
        color: #f3f4f6;
    }
    .dark .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #4b5563;
    }
    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #008060;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    async function handleSave(e, goBack) {
        e.preventDefault();
        const name = document.getElementById("band-name").value.trim();
        const type = document.getElementById("band-type").value;
        const adj = document.getElementById("band-adj").value.trim();
        
        // Get selected venues using jQuery for Select2
        const selected = $('#band-venues').val();

        if (!name || !type || !adj) return;

        const data = {
            location_band_name: name,
            location_band_type: type,
            adjustment: adj,
            venue: selected || []
        };

        try {
            const response = await fetch('/admin/course-price/location-bands', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                showToast("Location Band added successfully!");
                if (goBack) {
                    setTimeout(() => { window.location.href = "/admin/course-price/location-bands"; }, 900);
                } else {
                    setTimeout(() => {
                        document.getElementById("band-name").value = "";
                        document.getElementById("band-type").value = "";
                        document.getElementById("band-adj").value = "";
                        $('#band-venues').val(null).trigger('change');
                    }, 900);
                }
            } else {
                alert("Failed to save. Please try again.");
            }
        } catch(error) {
            console.error(error);
            alert("Error saving location band.");
        }
    }

    function showToast(msg) {
        const t = document.getElementById("toast");
        document.getElementById("toast-message").innerText = msg;
        t.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        setTimeout(() => { t.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm"; }, 3500);
    }

    // Initialize Select2 immediately (bypassing document.ready for SPA/Livewire compatibility)
    if (typeof jQuery !== 'undefined') {
        jQuery('#band-venues').select2({
            placeholder: "Select venues...",
            allowClear: true,
            width: '100%'
        });
    }
</script>
@endsection
