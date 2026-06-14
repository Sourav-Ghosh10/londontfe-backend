@extends('admin.layout')

@section('content')
<div class="w-full pb-12">

    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/courses/venues" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white" id="header-venue-title">Venue Details</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" id="header-venue-subtitle">View detailed information, status, and related sub-venues</p>
        </div>
    </div>

    <!-- Venue Dashboard Layout -->
    <div id="venue-content-container" class="space-y-6">

        <!-- 1. Beautiful Hero Banner Card -->
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-all duration-300">
            <!-- Banner Image with Gradient Overlay -->
            <div class="h-60 sm:h-72 w-full relative">
                <img id="venue-hero-banner" src="" alt="Venue Banner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                
                <!-- Floating Info over Banner -->
                <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Flag / Country Thumbnail -->
                        <div class="w-16 h-16 rounded-xl border-2 border-white/90 shadow-lg overflow-hidden bg-white/10 backdrop-blur-md shrink-0">
                            <img id="venue-hero-flag-img" src="" alt="Flag" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span id="venue-hero-region" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/25 text-emerald-400 border border-emerald-500/35">Region</span>
                            <h2 id="venue-hero-name" class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Athens</h2>
                            <p id="venue-hero-country" class="text-sm text-gray-350 font-medium">Greece</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- 2. Dual Column Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Left 2 Columns: Detailed info & Content -->
            <div class="xl:col-span-2 space-y-6">

                <!-- Description Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#008060]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Venue Description
                        </h3>
                    </div>
                    <div class="p-5 text-gray-700 dark:text-gray-300 text-sm leading-relaxed prose dark:prose-invert" id="view-venue-description">
                        <!-- Loaded dynamically -->
                    </div>
                </div>

                <!-- Featured Highlights Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#008060]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Featured Highlights &amp; Promo Text
                        </h3>
                    </div>
                    <div class="p-5 text-sm text-gray-700 dark:text-gray-300 leading-relaxed prose dark:prose-invert" id="view-venue-featured-text">
                        <!-- Loaded dynamically -->
                    </div>
                </div>

            </div>

            <!-- Right 1 Column: Associated Sub-venues and Metadata -->
            <div class="space-y-6">

                <!-- Associated Sub-Venues Card -->


                <!-- SEO Metadata Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#008060]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO Configuration
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Meta Title</span>
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200" id="view-meta-title">Not set</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Meta Description</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed" id="view-meta-desc">Not set</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Error State Container (hidden by default) -->
    <div id="error-container" class="hidden py-16 text-center">
        <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">Venue Not Found</h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">The requested venue ID is invalid or does not exist in the database.</p>
        <a href="/admin/courses/venues" class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-4 py-2 rounded-md transition-colors shadow-sm">
            Back to Venues
        </a>
    </div>

</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
    const venue = @json($mappedVenue);

    document.addEventListener("DOMContentLoaded", () => {
        if (!venue || !venue.id) {
            showError();
            return;
        }
        renderVenueDetails(venue);
    });

    function showError() {
        document.getElementById("venue-content-container").classList.add("hidden");
        document.getElementById("error-container").classList.remove("hidden");
    }

    function renderVenueDetails(v) {
        // Set Header details
        document.getElementById("header-venue-title").textContent = v.name + " Venue Details";
        document.getElementById("header-venue-subtitle").textContent = `Detailed overview and configuration of our ${v.name} training facilities`;

        // Set Hero elements
        document.getElementById("venue-hero-banner").src = v.image;
        document.getElementById("venue-hero-banner").alt = `${v.name} Banner`;
        document.getElementById("venue-hero-flag-img").src = v.image; // Using high quality city image as circular thumbnail
        document.getElementById("venue-hero-flag-img").alt = `${v.name} Flag`;
        
        document.getElementById("venue-hero-name").textContent = v.name;
        document.getElementById("venue-hero-country").textContent = v.flag;
        document.getElementById("venue-hero-region").textContent = v.region;

        // Set descriptions
        document.getElementById("view-venue-description").innerHTML = v.description || "<p class='text-gray-400 italic'>No description provided yet.</p>";
        document.getElementById("view-venue-featured-text").innerHTML = v.featuredText || "<p class='text-gray-400 italic'>No promotional text provided.</p>";

        // Set SEO
        document.getElementById("view-meta-title").textContent = v.metaTitle || "Not set";
        document.getElementById("view-meta-desc").textContent = v.metaDesc || "Not set";
    }
</script>
@endsection
