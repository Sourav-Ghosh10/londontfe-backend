<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        }
    </style>
    @stack('head')
</head>

<body
    class="flex h-screen overflow-hidden bg-[#f6f6f7] dark:bg-gray-900 text-[#202223] dark:text-gray-200 transition-colors duration-200">

    <!-- Custom Top Loading Bar -->
    <div id="loading-bar"
        class="fixed top-0 left-0 right-0 h-0.5 bg-[#008060] transform -translate-x-full transition-transform duration-300 ease-out z-50">
    </div>


    <!-- Sidebar -->
    <aside
        class="w-64 bg-[#ebebeb] dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 flex flex-col transition-colors duration-200">
        <div class="h-14 flex items-center px-4 border-b border-gray-300 dark:border-gray-700 transition-colors">
            <span class="font-semibold text-lg text-gray-900 dark:text-white">London TFE</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
            <a href="/admin"
                class="flex items-center px-3 py-1.5 {{ Request::is('admin') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-3 {{ Request::is('admin') ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </a>

            <!-- Training Courses Dropdown -->
            <div class="space-y-1">
                <button type="button" onclick="toggleSidebarDropdown('training-courses-menu', 'training-courses-arrow')"
                    class="w-full flex items-center justify-between px-3 py-1.5 {{ Request::is('admin/courses*') || Request::is('admin/course-price*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200 focus:outline-none">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ Request::is('admin/courses*') || Request::is('admin/course-price*') ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        Courses
                    </span>
                    <svg id="training-courses-arrow"
                        class="w-4 h-4 transform transition-transform duration-200 {{ Request::is('admin/courses*') || Request::is('admin/course-price*') ? 'rotate-90' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <div id="training-courses-menu"
                    class="{{ Request::is('admin/courses*') || Request::is('admin/course-price*') ? '' : 'hidden' }} pl-8 space-y-1">
                    <a href="/admin/courses"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses') && !Request::is('admin/courses/popular*') && !Request::is('admin/courses/create*') && !Request::is('admin/courses/categories*') && !Request::is('admin/courses/venues*') && !Request::is('admin/courses/subvenues*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2.5"></span>
                        Course List
                    </a>
                    <a href="/admin/courses/categories"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses/categories*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-emerald-400 dark:bg-emerald-500 rounded-full mr-2.5"></span>
                        Categories
                    </a>
                    <a href="/admin/courses/popular"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses/popular*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-yellow-400 dark:bg-yellow-500 rounded-full mr-2.5"></span>
                        Popular Courses
                    </a>
                    <a href="/admin/courses/venues"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses/venues*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-blue-400 dark:bg-blue-500 rounded-full mr-2.5"></span>
                        Venues
                    </a>
                    <a href="/admin/courses/currencies"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses/currencies*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-amber-400 dark:bg-amber-500 rounded-full mr-2.5"></span>
                        Currency Rates
                    </a>
                    <a href="/admin/courses/promocodes"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/courses/promocodes*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-violet-400 dark:bg-violet-500 rounded-full mr-2.5"></span>
                        Promotion Code
                    </a>
                    <a href="/admin/course-price/tiers"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/course-price/tiers*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-red-400 dark:bg-red-500 rounded-full mr-2.5"></span>
                        Tier List
                    </a>
                    <a href="/admin/course-price/location-bands"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/course-price/location-bands*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-indigo-400 dark:bg-indigo-500 rounded-full mr-2.5"></span>
                        Location Band
                    </a>
                </div>
            </div>

            <!-- Blog Dropdown -->
            <div class="space-y-1">
                <button type="button" onclick="toggleSidebarDropdown('blog-menu', 'blog-arrow')"
                    class="w-full flex items-center justify-between px-3 py-1.5 {{ Request::is('admin/blog*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200 focus:outline-none">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ Request::is('admin/blog*') ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2">
                            </path>
                        </svg>
                        Blog
                    </span>
                    <svg id="blog-arrow"
                        class="w-4 h-4 transform transition-transform duration-200 {{ Request::is('admin/blog*') ? 'rotate-90' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <div id="blog-menu" class="{{ Request::is('admin/blog*') ? '' : 'hidden' }} pl-8 space-y-1">
                    <a href="/admin/blog"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/blog') && !Request::is('admin/blog/create*') && !Request::is('admin/blog/categories*') && !Request::is('admin/blog/approvals*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2.5"></span>
                        All Articles
                    </a>
                    <a href="/admin/blog/create"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/blog/create*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-emerald-400 dark:bg-emerald-500 rounded-full mr-2.5"></span>
                        New Article
                    </a>
                    <a href="/admin/blog/categories"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/blog/categories*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-blue-400 dark:bg-blue-500 rounded-full mr-2.5"></span>
                        Categories
                    </a>
                </div>
            </div>

            {{-- Website --}}
            <div x-data="{ open: {{ Request::is('admin/website*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-1.5 {{ Request::is('admin/website*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        Website
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                    <a href="/admin/website/clients"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/clients*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-2.5"></span>
                        Our Clients
                    </a>
                    <a href="/admin/website/banners"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/banners*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2.5"></span>
                        Home Page Banner
                    </a>
                    <a href="/admin/website/pages"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/pages*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-purple-400 rounded-full mr-2.5"></span>
                        Page Content
                    </a>
                    <a href="/admin/website/accreditation"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/accreditation*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2.5"></span>
                        Accreditation Body
                    </a>
                    <a href="/admin/website/autoreply"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/autoreply*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-2.5"></span>
                        Email Autoreply
                    </a>
                    <a href="/admin/website/testimonials"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/testimonials*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-pink-400 rounded-full mr-2.5"></span>
                        Testimonials
                    </a>
                    <a href="/admin/website/gallery"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/website/gallery*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full mr-2.5"></span>
                        Media
                    </a>
                </div>
            </div>

            {{-- Logs --}}
            <div x-data="{ open: {{ Request::is('admin/logs*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-1.5 {{ Request::is('admin/logs*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Logs
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                    <a href="/admin/logs/quick-enquiry"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/quick-enquiry*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-sky-400 rounded-full mr-2.5"></span>
                        Quick Enquiry Event
                    </a>
                    <a href="/admin/logs/download-outline"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/download-outline*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-teal-400 rounded-full mr-2.5"></span>
                        Download Full Outline
                    </a>
                    <a href="/admin/logs/details-checkout"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/details-checkout*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full mr-2.5"></span>
                        Details Checkout
                    </a>
                    <a href="/admin/logs/cart"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/cart*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-2.5"></span>
                        Cart
                    </a>
                    <a href="/admin/logs/before-payment"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/before-payment*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-2.5"></span>
                        Before Payment
                    </a>
                    <a href="/admin/logs/after-checkout"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/after-checkout*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2.5"></span>
                        After Checkout
                    </a>
                    <a href="/admin/logs/coupon"
                        class="flex items-center px-3 py-1.5 {{ Request::is('admin/logs/coupon*') ? 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-xs transition-colors duration-200">
                        <span class="w-1.5 h-1.5 bg-pink-400 rounded-full mr-2.5"></span>
                        Coupon
                    </a>
                </div>
            </div>

            <a href="/admin/users"
                class="flex items-center px-3 py-1.5 {{ Request::is('admin/users*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} rounded-md font-medium text-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-3 {{ Request::is('admin/users*') ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Users
            </a>
            <a href="#"
                class="flex items-center px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md font-medium text-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Topbar -->
        <header
            class="h-14 bg-white dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 flex items-center justify-between px-6 z-10 transition-colors duration-200">
            <div class="w-96">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text"
                        class="w-full bg-[#f6f6f7] dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md pl-10 pr-4 py-1.5 focus:bg-white dark:focus:bg-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                        placeholder="Search...">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button"
                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5 transition-colors">
                    <!-- Dark icon -->
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <!-- Light icon -->
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <button
                    class="bg-[#202223] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 transition-colors text-white text-sm font-semibold px-3 py-1.5 rounded-md">Admin</button>
            </div>
        </header>

        <!-- Page Content -->
        <main id="main-content" class="flex-1 overflow-y-auto p-6 transition-opacity duration-200 opacity-100">
            @yield('content')
        </main>

    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function () {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                // if NOT set via local storage previously
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });

        function toggleSidebarDropdown(menuId, arrowId) {
            var menu = document.getElementById(menuId);
            var arrow = document.getElementById(arrowId);
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                arrow.classList.add('rotate-90');
            } else {
                menu.classList.add('hidden');
                arrow.classList.remove('rotate-90');
            }
        }

        // ================= PJAX SINGLE PAGE APP ROUTER =================
        function startLoading() {
            const bar = document.getElementById('loading-bar');
            bar.style.transition = 'transform 3s cubic-bezier(0.1, 0.8, 0.1, 1)';
            bar.style.transform = 'translateX(-30%)'; // Move to 70% loaded
        }

        function stopLoading() {
            const bar = document.getElementById('loading-bar');
            bar.style.transition = 'transform 0.3s ease-out';
            bar.style.transform = 'translateX(0%)'; // 100% loaded
            setTimeout(() => {
                bar.style.transition = 'none';
                bar.style.transform = 'translateX(-100%)';
            }, 400);
        }

        function loadStylesheets(container) {
            const links = container.querySelectorAll('link[rel="stylesheet"]');
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !document.querySelector(`link[href="${href}"]`)) {
                    const newLink = document.createElement('link');
                    Array.from(link.attributes).forEach(attr => newLink.setAttribute(attr.name, attr.value));
                    document.head.appendChild(newLink);
                }
            });
        }

        function executeScripts(container) {
            const scripts = container.querySelectorAll('script');
            const scriptsToLoad = [];

            scripts.forEach(oldScript => {
                if (oldScript.src) {
                    if (document.querySelector(`script[src="${oldScript.src}"]`)) {
                        return;
                    }
                }
                scriptsToLoad.push(oldScript);
            });

            if (scriptsToLoad.length === 0) {
                document.dispatchEvent(new Event('DOMContentLoaded'));
                return;
            }

            let index = 0;
            function loadNext() {
                if (index >= scriptsToLoad.length) {
                    document.dispatchEvent(new Event('DOMContentLoaded'));
                    return;
                }

                const oldScript = scriptsToLoad[index++];
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));

                if (oldScript.src) {
                    newScript.onload = loadNext;
                    newScript.onerror = loadNext;
                    document.body.appendChild(newScript);
                } else {
                    newScript.textContent = oldScript.textContent;
                    document.body.appendChild(newScript);
                    loadNext();
                }
            }

            loadNext();
        }

        function updateActiveSidebarLinks(path) {
            const normPath = path.replace(/\/$/, '') || '/';
            const links = document.querySelectorAll('aside nav a');

            links.forEach(link => {
                const href = link.getAttribute('href');
                if (!href) return;

                const isDashboard = href === '/admin';
                const isCategories = href === '/admin/courses/categories';
                const isPopular = href === '/admin/courses/popular';
                const isVenues = href === '/admin/courses/venues';
                const isSubVenues = href === '/admin/courses/subvenues';
                const isCurrencies = href === '/admin/courses/currencies';
                const isCourseList = href === '/admin/courses';

                let isActive = false;

                if (isDashboard) {
                    isActive = normPath === '/admin';
                } else if (isCategories) {
                    isActive = normPath.startsWith('/admin/courses/categories');
                } else if (isPopular) {
                    isActive = normPath.startsWith('/admin/courses/popular');
                } else if (isVenues) {
                    isActive = normPath.startsWith('/admin/courses/venues');
                } else if (isSubVenues) {
                    isActive = normPath.startsWith('/admin/courses/subvenues');
                } else if (isCurrencies) {
                    isActive = normPath.startsWith('/admin/courses/currencies');
                } else if (isCourseList) {
                    isActive = normPath === '/admin/courses' || normPath.startsWith('/admin/courses/create');
                }

                if (link.closest('#training-courses-menu')) {
                    if (isActive) {
                        link.className = "flex items-center px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-md font-medium text-xs transition-colors duration-200";
                    } else {
                        link.className = "flex items-center px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md font-medium text-xs transition-colors duration-200";
                    }
                } else {
                    if (isActive) {
                        link.className = "flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-md font-medium text-sm transition-colors duration-200";
                    } else {
                        link.className = "flex items-center px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md font-medium text-sm transition-colors duration-200";
                    }
                }
            });

            const coursesBtn = document.querySelector('button[onclick*="training-courses-menu"]');
            if (coursesBtn) {
                const isCoursesActive = normPath.startsWith('/admin/courses');
                if (isCoursesActive) {
                    coursesBtn.className = "w-full flex items-center justify-between px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-md font-medium text-sm transition-colors duration-200 focus:outline-none";
                    coursesBtn.querySelector('svg').className = "w-5 h-5 mr-3 text-gray-600 dark:text-gray-300";
                } else {
                    coursesBtn.className = "w-full flex items-center justify-between px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md font-medium text-sm transition-colors duration-200 focus:outline-none";
                    coursesBtn.querySelector('svg').className = "w-5 h-5 mr-3 text-gray-500 dark:text-gray-400";
                }
            }
        }

        function navigateTo(url, pushState = true) {
            startLoading();
            const mainContent = document.getElementById('main-content');

            mainContent.classList.remove('opacity-100');
            mainContent.classList.add('opacity-0');

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        window.location.href = url;
                        return null;
                    }
                    return response.text();
                })
                .then(html => {
                    if (!html) return;

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTitle = doc.querySelector('title');
                    if (newTitle) {
                        document.title = newTitle.textContent;
                    }

                    const newMain = doc.querySelector('#main-content');
                    if (newMain) {
                        mainContent.innerHTML = newMain.innerHTML;
                    } else {
                        const fallbackMain = doc.querySelector('main');
                        if (fallbackMain) {
                            mainContent.innerHTML = fallbackMain.innerHTML;
                        }
                    }

                    if (pushState) {
                        history.pushState(null, '', url);
                    }

                    loadStylesheets(doc);
                    executeScripts(mainContent);

                    updateActiveSidebarLinks(url);

                    setTimeout(() => {
                        mainContent.classList.remove('opacity-0');
                        mainContent.classList.add('opacity-100');
                        stopLoading();
                    }, 100);
                })
                .catch(err => {
                    console.error('PJAX Navigation Error:', err);
                    window.location.href = url;
                });
        }

        window.pjaxNavigate = navigateTo;

        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href) return;

            if (href.startsWith('http') || href.startsWith('//') || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                return;
            }

            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || link.getAttribute('target') === '_blank') {
                return;
            }

            e.preventDefault();
            navigateTo(href);
        });

        window.addEventListener('popstate', function () {
            navigateTo(window.location.pathname, false);
        });
    </script>
</body>


</html>