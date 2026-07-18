@extends('admin.layout')

@section('content')
<!-- Jodit stylesheet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.css"/>

<div class="w-full pb-12">

    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/courses/venues" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Venue</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new global training course venue with customized descriptions, rich content, flag, and SEO options</p>
        </div>
    </div>

    <!-- Main Form -->
    <form onsubmit="handleFormSubmit(event)" id="venue-form">
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- ── LEFT COLUMN: Rich Content & Media ─────────────────────── -->
            <div class="flex-1 space-y-6">

                <!-- 1. General Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            General Information
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Venue Name -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Venue Name <span class="text-red-500">*</span></label>
                                <input type="text" id="venue-name" required placeholder="e.g. Athens" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" oninput="clearError('name')">
                                <p id="error-name" class="hidden mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span id="error-name-text"></span>
                                </p>
                            </div>

                            <!-- Country / Flag Name -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Country / Flag Image Name <span class="text-red-500">*</span></label>
                                <input type="text" id="flag-name" required placeholder="e.g. Greece" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" oninput="clearError('flag')">
                                <p id="error-flag" class="hidden mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span id="error-flag-text"></span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <!-- Region -->
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Region <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="venue-region" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer" onchange="clearError('region')">
                                    <option value="">Select Region</option>
                                    <option value="Europe">Europe</option>
                                    <option value="Middle East">Middle East</option>
                                    <option value="Rest of World">Rest of World</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <p id="error-region" class="hidden mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span id="error-region-text"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Rich Content Fields -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Rich Text Content
                        </h2>
                    </div>
                    <div class="p-5 space-y-6">

                        <!-- Venue Description -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Venue Description</label>
                            <textarea id="editor-description" name="description"></textarea>
                        </div>

                        <!-- Venue Featured Text -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Venue Featured Text</label>
                            <textarea id="editor-featured-text" name="featured-text"></textarea>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ── RIGHT COLUMN: Media & SEO ────────────────────────────── -->
            <div class="xl:w-80 space-y-6 flex-shrink-0">

                <!-- Actions Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Publish Action</h2>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <!-- Seals Switch Status dropdown -->
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status *</label>
                            <select id="venue-status" required class="w-full text-xs bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060]">
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
                        <a href="/admin/courses/venues" class="w-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-750 dark:hover:text-gray-200 py-2 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Media Assets Upload Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Media Assets
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">

                        <!-- Banner Image -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Banner Image</label>
                            <div class="border border-dashed border-gray-300 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-lg p-4 text-center cursor-pointer transition-colors" onclick="triggerFileInput('file-banner')">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="block mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400">Upload a file</span>
                                <div id="img-container-banner" class="mt-3 mb-2 flex justify-center hidden">
                                    <img id="img-preview-banner" src="" alt="Banner Image" class="h-24 w-auto object-contain rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                                </div>
                                <span class="block mt-1 text-[10px] text-gray-400 dark:text-gray-500" id="preview-banner">No file selected</span>
                                <input type="file" id="file-banner" onchange="handleFileSelected(this, 'preview-banner', 'img-preview-banner', 'img-container-banner')" accept="image/*" class="hidden">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SEO & Meta Configuration Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO &amp; Metadata
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Title <span class="text-red-500">*</span></label>
                            <input type="text" id="meta-title" required placeholder="e.g. Athens Training Venues" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" oninput="clearError('meta_title')">
                            <p id="error-meta_title" class="hidden mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span id="error-meta_title-text"></span>
                            </p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Description <span class="text-red-500">*</span></label>
                            <textarea id="meta-description" required rows="4" placeholder="Brief SEO description (150-160 characters)..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none" oninput="clearError('meta_description')"></textarea>
                            <p id="error-meta_description" class="hidden mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span id="error-meta_description-text"></span>
                            </p>
                        </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.js"></script>
<script>
    var editors = {};

    // Initialize editors using Jodit
    document.addEventListener('DOMContentLoaded', () => {
        editors['description'] = Jodit.make('#editor-description', {
            height: 200,
            placeholder: 'Venue Description...'
        });
        editors['featured-text'] = Jodit.make('#editor-featured-text', {
            height: 200,
            placeholder: 'Venue Featured Text...'
        });
    });

    // Helper functions for file uploads
    function triggerFileInput(id) {
        document.getElementById(id).click();
    }

    function handleFileSelected(input, previewId, imgPreviewId, containerId) {
        const span = document.getElementById(previewId);
        const img = document.getElementById(imgPreviewId);
        const container = document.getElementById(containerId);
        
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            span.textContent = file.name;
            span.classList.remove("text-gray-400", "dark:text-gray-500");
            span.classList.add("text-emerald-600", "dark:text-emerald-400", "font-medium");
            
            // Show preview
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (img) img.src = e.target.result;
                    if (container) container.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        } else {
            span.textContent = "No file selected";
            span.classList.add("text-gray-400", "dark:text-gray-500");
            span.classList.remove("text-emerald-600", "dark:text-emerald-400", "font-medium");
        }
    }

    // Submit handler
    function handleFormSubmit(e) {
        e.preventDefault();
        saveVenueData(false);
    }

    // Save and return to list
    function submitAndReturn() {
        saveVenueData(true);
    }

    // --- Validation Helpers ---
    function showFieldError(field, message) {
        const el = document.getElementById('error-' + field);
        const txt = document.getElementById('error-' + field + '-text');
        const input = document.getElementById(
            field === 'name' ? 'venue-name' :
            field === 'flag' ? 'flag-name' :
            field === 'region' ? 'venue-region' :
            field === 'meta_title' ? 'meta-title' :
            field === 'meta_description' ? 'meta-description' : field
        );
        if (el && txt) {
            txt.textContent = message;
            el.classList.remove('hidden');
        }
        if (input) {
            input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.remove('border-gray-300', 'dark:border-gray-600', 'focus:ring-[#008060]', 'focus:border-[#008060]');
        }
    }

    function clearError(field) {
        const el = document.getElementById('error-' + field);
        const input = document.getElementById(
            field === 'name' ? 'venue-name' :
            field === 'flag' ? 'flag-name' :
            field === 'region' ? 'venue-region' :
            field === 'meta_title' ? 'meta-title' :
            field === 'meta_description' ? 'meta-description' : field
        );
        if (el) el.classList.add('hidden');
        if (input) {
            input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.add('border-gray-300', 'dark:border-gray-600', 'focus:ring-[#008060]', 'focus:border-[#008060]');
        }
    }

    // Clear all errors
    function clearAllErrors() {
        ['name', 'flag', 'region', 'meta_title', 'meta_description'].forEach(clearError);
    }

    async function saveVenueData(shouldRedirect) {
        clearAllErrors();

        let hasError = false;
        const nameVal = document.getElementById("venue-name").value.trim();
        const flagVal = document.getElementById("flag-name").value.trim();
        const regionVal = document.getElementById("venue-region").value;
        const metaTitleVal = document.getElementById("meta-title").value.trim();
        const metaDescVal = document.getElementById("meta-description").value.trim();

        if (!nameVal) { showFieldError('name', 'Venue name is required.'); hasError = true; }
        if (!flagVal) { showFieldError('flag', 'Country / Flag Name is required.'); hasError = true; }
        if (!regionVal) { showFieldError('region', 'Region is required.'); hasError = true; }
        if (!metaTitleVal) { showFieldError('meta_title', 'Meta Title is required.'); hasError = true; }
        if (!metaDescVal) { showFieldError('meta_description', 'Meta Description is required.'); hasError = true; }

        if (hasError) {
            const firstError = document.querySelector('[id^="error-"]:not(.hidden)');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const formData = new FormData();
        formData.append('name', nameVal);
        formData.append('flag', flagVal);
        formData.append('region', regionVal);
        formData.append('status', document.getElementById("venue-status").value);
        formData.append('meta_title', metaTitleVal);
        formData.append('meta_description', metaDescVal);

        // Add rich text fields directly from Jodit editors
        formData.append('description', editors['description'] ? editors['description'].value : '');
        formData.append('featured_text', editors['featured-text'] ? editors['featured-text'].value : '');

        // Add file fields
        const bannerFile = document.getElementById("file-banner").files[0];
        if (bannerFile) formData.append('banner_image', bannerFile);

        // CSRF Token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch('/admin/courses/venues', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token || '' },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message || "Venue saved successfully!", "success");
                if (shouldRedirect) {
                    setTimeout(() => { window.location.href = "/admin/courses/venues"; }, 1200);
                }
            } else if (response.status === 422 && result.errors) {
                Object.entries(result.errors).forEach(([field, messages]) => {
                    showFieldError(field, messages[0]);
                });
                const firstError = document.querySelector('[id^="error-"]:not(.hidden)');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showToast("Please fix the errors below.", "error");
            } else {
                showToast(result.message || "Failed to save venue.", "error");
            }
        } catch (error) {
            console.error(error);
            showToast("An error occurred while saving the venue.", "error");
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
        } else if (type === "error") {
            toastIconWrapper.className = "rounded-full p-1 bg-red-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
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
