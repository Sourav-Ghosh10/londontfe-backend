@extends('admin.layout')

@section('content')
<!-- Quill rich text styles & dark mode adjustments -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    /* Premium Shopify/modern inspired styles for custom Quill editors */
    .ql-toolbar.ql-snow {
        border: 1px solid #d1d5db !important;
        background-color: #f9fafb;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
        padding: 6px 12px !important;
    }
    .ql-container.ql-snow {
        border: 1px solid #d1d5db !important;
        background-color: #ffffff;
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ql-editor {
        min-height: 140px;
        color: #1f2937;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .ql-editor.ql-blank::before {
        color: #9ca3af !important;
        font-style: normal !important;
    }
    
    /* Sleek dark mode rules for Quill */
    .dark .ql-toolbar.ql-snow {
        border-color: #4b5563 !important;
        background-color: #1f2937;
    }
    .dark .ql-container.ql-snow {
        border-color: #4b5563 !important;
        background-color: #374151;
    }
    .dark .ql-editor {
        color: #f3f4f6;
    }
    .dark .ql-stroke {
        stroke: #9ca3af !important;
    }
    .dark .ql-fill {
        fill: #9ca3af !important;
    }
    .dark .ql-picker {
        color: #9ca3af !important;
    }
    .dark .ql-picker-options {
        background-color: #1f2937 !important;
        border-color: #4b5563 !important;
    }
</style>

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
                                <input type="text" id="venue-name" required placeholder="e.g. Athens" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>

                            <!-- Country / Flag Name -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Country / Flag Image Name <span class="text-red-500">*</span></label>
                                <input type="text" id="flag-name" required placeholder="e.g. Greece" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>

                        <div>
                            <!-- Region -->
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Region <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="venue-region" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
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
                        </div>
                    </div>
                </div>

                <!-- 2. Rich Content Fields -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30">
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
                            <div class="relative">
                                <div id="toolbar-description" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                                    <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                                    <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                                    <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                                    <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                                    <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                        <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                                    </button>
                                    <button type="button" class="custom-html-btn description-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('description')">
                                        &lt;&gt;
                                    </button>
                                </div>
                                <div id="editor-description" class="min-h-[140px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                                <textarea id="source-description" class="hidden w-full min-h-[140px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                                <input type="hidden" name="description">
                            </div>
                        </div>

                        <!-- Venue Featured Text -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Venue Featured Text</label>
                            <div class="relative">
                                <div id="toolbar-featured-text" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                                    <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                                    <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                                    <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                                    <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                                    <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                        <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                                    </button>
                                    <button type="button" class="custom-html-btn featured-text-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('featured-text')">
                                        &lt;&gt;
                                    </button>
                                </div>
                                <div id="editor-featured-text" class="min-h-[140px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                                <textarea id="source-featured-text" class="hidden w-full min-h-[140px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                                <input type="hidden" name="featured-text">
                            </div>
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
                                <span class="block mt-1 text-[10px] text-gray-400 dark:text-gray-500" id="preview-banner">No file selected</span>
                                <input type="file" id="file-banner" onchange="handleFileSelected(this, 'preview-banner')" class="hidden">
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
                            <input type="text" id="meta-title" required placeholder="e.g. Athens Training Venues" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Description <span class="text-red-500">*</span></label>
                            <textarea id="meta-description" required rows="4" placeholder="Brief SEO description (150-160 characters)..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
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
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    const editors = {};

    // Initialise a Quill Instance
    function setupQuill(id) {
        const ql = new Quill('#editor-' + id, {
            theme: 'snow',
            modules: {
                toolbar: '#toolbar-' + id
            }
        });
        
        editors[id] = ql;

        // Synchronize content to hidden form input
        ql.on('text-change', function() {
            const html = ql.root.innerHTML;
            const input = document.querySelector(`input[name="${id}"]`);
            if (input) input.value = html;
        });
    }

    // Initialize all 3 editors
    document.addEventListener('DOMContentLoaded', () => {
        setupQuill('description');
        setupQuill('featured-text');
    });

    // Custom HTML source code viewer toggle function
    window.toggleQuillSourceCode = function(id) {
        const editorEl = document.getElementById('editor-' + id);
        const textareaEl = document.getElementById('source-' + id);
        const ql = editors[id];
        const isSourceMode = textareaEl.classList.contains('active-source-mode');
        const toolbar = document.getElementById('toolbar-' + id);
        const btn = toolbar.querySelector('.custom-html-btn');

        if (!isSourceMode) {
            // Activate HTML Source view
            textareaEl.classList.add('active-source-mode');
            textareaEl.classList.remove('hidden');
            editorEl.classList.add('hidden');
            textareaEl.value = ql.root.innerHTML;
            
            btn.classList.add('bg-[#008060]', 'text-white');
            btn.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
            
            toolbar.querySelectorAll('button:not(.custom-html-btn)').forEach(b => {
                b.disabled = true;
                b.style.opacity = '0.35';
                b.style.pointerEvents = 'none';
            });
        } else {
            // Deactivate HTML Source view
            textareaEl.classList.remove('active-source-mode');
            textareaEl.classList.add('hidden');
            editorEl.classList.remove('hidden');
            
            ql.root.innerHTML = textareaEl.value;
            const input = document.querySelector(`input[name="${id}"]`);
            if (input) input.value = textareaEl.value;

            btn.classList.remove('bg-[#008060]', 'text-white');
            btn.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
            
            toolbar.querySelectorAll('button:not(.custom-html-btn)').forEach(b => {
                b.disabled = false;
                b.style.opacity = '1';
                b.style.pointerEvents = 'auto';
            });
        }
    };

    // Helper functions for file uploads
    function triggerFileInput(id) {
        document.getElementById(id).click();
    }

    function handleFileSelected(input, previewId) {
        const span = document.getElementById(previewId);
        if (input.files && input.files.length > 0) {
            span.textContent = input.files[0].name;
            span.classList.remove("text-gray-400", "dark:text-gray-500");
            span.classList.add("text-emerald-600", "dark:text-emerald-400", "font-medium");
        } else {
            span.textContent = "No file selected";
            span.classList.add("text-gray-400", "dark:text-gray-500");
            span.classList.remove("text-emerald-600", "dark:text-emerald-400", "font-medium");
        }
    }

    // Submit handler (Mock API action)
    function handleFormSubmit(e) {
        e.preventDefault();
        saveVenueData(false);
    }

    // Save and return to list
    function submitAndReturn() {
        // Validate required fields manually since button is type="button"
        const name = document.getElementById("venue-name");
        const flag = document.getElementById("flag-name");
        const region = document.getElementById("venue-region");
        const metaTitle = document.getElementById("meta-title");
        const metaDesc = document.getElementById("meta-description");

        if (!name.checkValidity()) {
            name.reportValidity();
            return;
        }
        if (!flag.checkValidity()) {
            flag.reportValidity();
            return;
        }
        if (!region.checkValidity()) {
            region.reportValidity();
            return;
        }
        if (!metaTitle.checkValidity()) {
            metaTitle.reportValidity();
            return;
        }
        if (!metaDesc.checkValidity()) {
            metaDesc.reportValidity();
            return;
        }

        saveVenueData(true);
    }

    function saveVenueData(shouldRedirect) {
        const venueName = document.getElementById("venue-name").value;
        showToast(`Venue "${venueName}" created successfully!`, "success");

        if (shouldRedirect) {
            setTimeout(() => {
                window.location.href = "/admin/courses/venues";
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
