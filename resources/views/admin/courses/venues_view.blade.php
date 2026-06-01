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
    // Local database representing the primary venues database
    const primaryVenuesDb = [
        { 
            id: 1, 
            name: "Athens", 
            image: "https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=800&q=80", 
            flag: "Greece", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Avenue Vassilissis Sofias 46, Athina 106 76, Greece</p>",
            description: "<p>Athens is a historic and majestic training location that connects ancient history with modern commercial capabilities. The training facility resides right in the heart of downtown Athens close to the historic landmarks and local corporate hubs.</p>",
            featuredText: "<p>Experience fully immersive interactive training with modern high-speed workspace facilities in central Greece.</p>",
            metaTitle: "Athens Global Training Venue | London TFE",
            metaDesc: "Discover premium courses in Athens, Greece. Located close to beautiful sights and corporate headquarters."
        },
        { 
            id: 2, 
            name: "Dubai", 
            image: "https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80", 
            flag: "United Arab Emirates", 
            region: "Middle East", 
            featured: true, 
            sealsStatus: true,
            address: "<p>Marina Heights, Tower A, 24th Floor, Dubai Marina, UAE</p>",
            description: "<p>Dubai represents a thriving, futuristic global business capital. Known for highly sophisticated workspace and luxury facilities, our Dubai Marina venue provides perfect corporate suites equipped with high-tech conference spaces.</p>",
            featuredText: "<p>Premium featured venue in the UAE providing 5-star executive lounges, high-tech lecture halls, and beautiful marina views.</p>",
            metaTitle: "Dubai Marina Executive Courses | London TFE",
            metaDesc: "Advance your corporate skills at our premium Dubai Marina training facility. Enjoy luxury accommodations and smart hubs."
        },
        { 
            id: 3, 
            name: "Vienna", 
            image: "https://images.unsplash.com/photo-1516550893923-42d28e5677af?auto=format&fit=crop&w=800&q=80", 
            flag: "Austria", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Schönbrunner Schloßstraße 47, 1130 Wien, Austria</p>",
            description: "<p>Vienna offers class and heritage combined with state-of-the-art administrative services. Ideal for delegates who appreciate cultural marvels and quiet study settings close to the Schönbrunn palace gardens.</p>",
            featuredText: "<p>Beautiful garden spaces, high-speed campus amenities, and proximity to major rail hubs.</p>",
            metaTitle: "Vienna Training Venues | London TFE",
            metaDesc: "Take professional courses in Vienna, Austria. Highly rated conference halls near majestic palaces."
        },
        { 
            id: 4, 
            name: "London", 
            image: "https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=800&q=80", 
            flag: "United Kingdom", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Kensington High St, London W8 5SA, United Kingdom</p>",
            description: "<p>London is our flagship training center city. Boasting advanced educational facilities, this hub in historic Kensington accommodates hundreds of global corporate delegates every month.</p>",
            featuredText: "<p>Fully equipped computing centers, quiet study lounges, and direct access to transit stations.</p>",
            metaTitle: "London Corporate Seminars | London TFE",
            metaDesc: "Join our flagship courses in the United Kingdom. Located in the beautiful borough of Kensington."
        },
        { 
            id: 5, 
            name: "Copenhagen", 
            image: "https://images.unsplash.com/photo-1513622470522-26c3c8a854bc?auto=format&fit=crop&w=800&q=80", 
            flag: "Denmark", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Nyhavn Canal 12, 1051 København, Denmark</p>",
            description: "<p>Copenhagen venue offers a unique Scandinavian experience. Fully modern, minimalist styling coupled with beautiful views of the historic canal harbor.</p>",
            featuredText: "<p>Sleek design, green credentials, and cozy community study rooms.</p>",
            metaTitle: "Copenhagen Courses - London TFE",
            metaDesc: "Explore professional training programs in Copenhagen, Denmark near Nyhavn Canal."
        },
        { 
            id: 6, 
            name: "Budapest", 
            image: "https://images.unsplash.com/photo-1565426960434-08f1b621eefb?auto=format&fit=crop&w=800&q=80", 
            flag: "Hungary", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Andrássy út 22, 1061 Budapest, Hungary</p>",
            description: "<p>Budapest provides a cost-effective and spectacular European location. Our venue along Andrássy Boulevard offers premium facilities and rich architectural scenery.</p>",
            featuredText: "<p>Historic training rooms, modern smartboards, and excellent city views.</p>",
            metaTitle: "Budapest Seminars - London TFE",
            metaDesc: "Sign up for courses in Budapest, Hungary. Budget-friendly European business school facilities."
        },
        { 
            id: 7, 
            name: "Stockholm", 
            image: "https://images.unsplash.com/photo-1509142168808-57d19c9e3650?auto=format&fit=crop&w=800&q=80", 
            flag: "Sweden", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Hamngatan 18, 111 47 Stockholm, Sweden</p>",
            description: "<p>Stockholm provides exceptional facilities designed for smart working. Our Sweden offices represent clean, airy spaces with high focus on sustainability.</p>",
            featuredText: "<p>Sustainable green building, direct metro connectivity, and premium tech tools.</p>",
            metaTitle: "Stockholm Training Center | London TFE",
            metaDesc: "Boost your credentials in Sweden. Smart classrooms designed with ergonomic and eco-friendly features."
        },
        { 
            id: 8, 
            name: "Istanbul", 
            image: "https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=800&q=80", 
            flag: "Turkey", 
            region: "Europe", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Istiklal Cd. No:142, 34430 Beyoğlu/İstanbul, Turkey</p>",
            description: "<p>Istanbul serves as a gorgeous bridge between eastern and western styles. Located on Istiklal Avenue, Beyoglu, this venue is fully active and features rich views and high-quality delegate spaces.</p>",
            featuredText: "<p>Stunning skyline views, historic learning center, and active community lounge.</p>",
            metaTitle: "Istanbul Business Programs | London TFE",
            metaDesc: "Take premium corporate workshops in Turkey. Located in the lively historic heart of Beyoglu."
        },
        { 
            id: 9, 
            name: "Kuala Lumpur", 
            image: "https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=800&q=80", 
            flag: "Malaysia", 
            region: "Rest of World", 
            featured: false, 
            sealsStatus: true,
            address: "<p>Jalan Ampang, 50450 Kuala Lumpur, Malaysia</p>",
            description: "<p>Kuala Lumpur serves our delegates in the Asia Pacific region. Situated in the shadow of the Petronas Towers, it is a key training hub offering top-class computing infrastructure.</p>",
            featuredText: "<p>Centrally located, high-speed fiber internet, and panoramic city skyscraper view.</p>",
            metaTitle: "Kuala Lumpur Training Venue | London TFE",
            metaDesc: "Discover dynamic skills courses in Malaysia. Smart facilities located directly in central Kuala Lumpur."
        }
    ];



    // Parse URL Parameter and Load Venue details
    document.addEventListener("DOMContentLoaded", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const venueId = parseInt(urlParams.get('id'));

        if (!venueId) {
            showError();
            return;
        }

        const venue = primaryVenuesDb.find(v => v.id === venueId);
        if (!venue) {
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
