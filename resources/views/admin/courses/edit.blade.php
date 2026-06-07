@extends('admin.layout')

@section('content')
<!-- Quill snow theme style & dark mode overrides -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    /* Sleek Shopify inspired styles for custom Quill editors */
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
        min-height: 160px;
        color: #1f2937;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .ql-editor.ql-blank::before {
        color: #9ca3af !important;
        font-style: normal !important;
    }
    
    /* Sleek dark mode rules */
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
    
    /* Select2 custom styling to match Tailwind theme */
    .select2-container--default .select2-selection--multiple {
        border-color: #d1d5db;
        border-radius: 0.375rem;
        min-height: 42px;
        padding-top: 2px;
        background-color: #f6f6f7;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #3b82f6; /* ring-blue-500 */
        outline: 0;
        box-shadow: 0 0 0 1px #3b82f6;
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
        background-color: #3b82f6;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/courses" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Course</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Fill in the details below to create a new training course</p>
        </div>
    </div>

    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- ── LEFT COLUMN ─────────────────────────────────────── -->
            <div class="flex-1 space-y-5">

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    </div>
                    <div class="p-5 space-y-4">

                        
                        <!-- Find Course ID & Course Unique ID -->
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Find Course Id</label>
                                <input type="text" name="find_course_id" value="{{ old('find_course_id', $course->find_course_id) }}" readonly class="w-full text-sm bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-500 rounded-md px-3 py-2.5 cursor-not-allowed">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">LTFE ID (Course Unique ID)</label>
                                <input type="text" name="course_unique_id" value="{{ old('course_unique_id', $course->course_unique_id) }}" readonly class="w-full text-sm bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-500 rounded-md px-3 py-2.5 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Accredible ID -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Accredible ID</label>
                            <input type="text" name="accredible_id" value="{{ old('accredible_id', $course->accredible_id) }}" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Training Type -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Training Type</label>
                            <select name="training_type" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="">Select training type</option>
                                <option value="classroom" {{ $course->training_type === 'classroom' ? 'selected' : '' }}>Class room</option>
                                <option value="online" {{ $course->training_type === 'online' ? 'selected' : '' }}>Online</option>
                                
                                
                            </select>
                        </div>

                        

                        <!-- Course Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Course Name <span class="text-red-500">*</span></label>
                            <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}" required placeholder="e.g. Advanced Leadership Programme" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Price Tier -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Price Tier</label>
                            <select id="price-tier-select" name="price_tier_id" onchange="togglePriceList()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="">Select Price Tier</option>
                                @foreach($priceTiers as $tier)
                                    <option value="{{ $tier->id }}" data-base-rate="{{ $tier->base_rate }}" data-daily-rate="{{ $tier->daily_rate }}" {{ $course->price_tier_id == $tier->id ? 'selected' : '' }}>{{ $tier->tier_name }}</option>
                                @endforeach
                            </select>
                            <p id="price-tier-hint" class="text-xs text-gray-400 dark:text-gray-500 mt-1 hidden">Price list will appear below after selecting a tier.</p>
                        </div>

                    </div>
                </div>

                <!-- Tax Price List (shown when Price Tier is selected) -->
                <div id="price-list-card" class="hidden bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Tax Price List</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Auto-calculated pricing tiers based on participant count</p>
                        </div>
                        <span id="selected-tier-badge" class="text-xs font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-2.5 py-0.5 rounded-full"></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 dark:border-gray-700">
                            <thead class="text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700" rowspan="2">Base Rate (Fixed)</th>
                                    <th class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700" rowspan="2">Daily Rate (Per Day)</th>
                                    <th class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700" rowspan="2">Days</th>
                                    <th class="px-4 py-3 text-center" colspan="2">Course Price</th>
                                </tr>
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <th class="px-4 py-2 text-center border-r border-gray-200 dark:border-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-400">GBP</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400">USD</th>
                                </tr>
                            </thead>
                            <tbody id="price-list-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Dynamic rows generated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Overview</h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-overview" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                            <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                            <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                            <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                            </button>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="ordered">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h1v3H3V4zm1 9H3v1h1v-1zm-1-4h1v1H3V9zm4-5h9v2H7V4zm0 6h9v2H7v-2zm0 6h9v2H7v-2zM3 13v1h1v-1H3z"/></svg>
                            </button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('overview')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-overview" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3">{!! old('overview', $course->overview) !!}</div>
                        <textarea id="source-overview" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none">{!! old('overview', $course->overview) !!}</textarea>
                        <input type="hidden" name="overview" value="{{ old('overview', $course->overview) }}">
                    </div>
                </div>

                <!-- Objective -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Objective <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-course_objective" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                            <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                            <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                            <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                            </button>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="ordered">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h1v3H3V4zm1 9H3v1h1v-1zm-1-4h1v1H3V9zm4-5h9v2H7V4zm0 6h9v2H7v-2zm0 6h9v2H7v-2zM3 13v1h1v-1H3z"/></svg>
                            </button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('course_objective')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-course_objective" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3">{!! old('course_objective', $course->course_objective) !!}</div>
                        <textarea id="source-course_objective" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none">{!! old('course_objective', $course->course_objective) !!}</textarea>
                        <input type="hidden" name="course_objective" value="{{ old('course_objective', $course->course_objective) }}">
                    </div>
                </div>

                <!-- Week One -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Week One</h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-course_meterial_content" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                            <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                            <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                            <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                            </button>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="ordered">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h1v3H3V4zm1 9H3v1h1v-1zm-1-4h1v1H3V9zm4-5h9v2H7V4zm0 6h9v2H7v-2zm0 6h9v2H7v-2zM3 13v1h1v-1H3z"/></svg>
                            </button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('course_meterial_content')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-course_meterial_content" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3">{!! old('course_meterial_content', $course->course_meterial_content) !!}</div>
                        <textarea id="source-course_meterial_content" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none">{!! old('course_meterial_content', $course->course_meterial_content) !!}</textarea>
                        <input type="hidden" name="course_meterial_content" value="{{ old('course_meterial_content', $course->course_meterial_content) }}">
                    </div>
                </div>

                <!-- Who Should Attend -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Who Should Attend</h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-wsa" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
                            <button type="button" class="ql-bold font-bold text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">B</button>
                            <button type="button" class="ql-italic italic text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">I</button>
                            <button type="button" class="ql-underline underline text-xs p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center">U</button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="bullet">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
                            </button>
                            <button type="button" class="ql-list p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center" value="ordered">
                                <svg class="w-3.5 h-3.5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h1v3H3V4zm1 9H3v1h1v-1zm-1-4h1v1H3V9zm4-5h9v2H7V4zm0 6h9v2H7v-2zm0 6h9v2H7v-2zM3 13v1h1v-1H3z"/></svg>
                            </button>
                            <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('wsa')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-wsa" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3">{!! old('wsa', $course->wsa) !!}</div>
                        <textarea id="source-wsa" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none">{!! old('wsa', $course->wsa) !!}</textarea>
                        <input type="hidden" name="wsa" value="{{ old('wsa', $course->wsa) }}">
                    </div>
                </div>

            </div>

            <!-- ── RIGHT SIDEBAR ───────────────────────────────────── -->
            <div class="xl:w-72 space-y-5">

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Actions</h2>
                    </div>
                    <div class="p-4 space-y-2">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] py-2.5 px-4 rounded-md transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Submit Course
                        </button>
                        <a href="/admin/courses" class="w-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                
                <!-- Primary & Secondary Category -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Categories <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Primary Category</label>
                            <select id="primary-category" name="primary_category" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('primary_category', $primaryCategory) == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Secondary Categories</label>
                            <select id="secondary-categories" name="secondary_category[]" multiple class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors h-32">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ in_array($cat->id, old('secondary_category', $secondaryCategories)) ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Course Accreditation -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Course Accreditation</h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <select id="course-accreditation" name="course_accreditation[]" multiple class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors h-32">
                                @if(isset($accreditations))
                                @foreach($accreditations as $acc)
                                    <option value="{{ $acc->id }}" {{ in_array($acc->id, old('course_accreditation', $selectedAccreditations)) ? 'selected' : '' }}>{{ $acc->accreditation_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Course Settings -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Course Settings</h2>
                    </div>
                    <div class="p-4 space-y-3">

                        <!-- Duration -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Duration</label>
                            <div class="flex gap-2">
                                <input type="number" name="course_duration" value="{{ old('course_duration', $course->course_duration) }}" placeholder="5" min="1" class="w-16 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                                <select name="course_duration_type" class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                    <option value="">Select duration type</option>
                                    <option value="1" {{ $course->course_duration_type == '1' ? 'selected' : '' }}>Day(s)</option>
                                    
                                    
                                    
                                </select>
                            </div>
                        </div>

                        <!-- CPD Hours -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">CPD Hours</label>
                            <input type="number" name="cpd_hours" value="{{ old('cpd_hours', $course->cpd_hours) }}" placeholder="e.g. 35" min="0" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Publish to XML -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Publish to XML</label>
                            <select name="is_publish" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="no" {{ $course->is_publish == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ $course->is_publish == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>

                        <!-- Is Certified -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Is Certified</label>
                            <select name="is_certified" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                
                                <option value="1" {{ $course->is_certified == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $course->is_certified == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <!-- Course Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Course Status <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="1" {{ $course->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $course->status == '0' ? 'selected' : '' }}>Inactive</option>
                                
                            </select>
                        </div>

                        

                    </div>
                </div>

                <!-- SEO & Meta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">SEO &amp; Meta</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Helps search engines index this course correctly</p>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tab Title</label>
                            <input type="text" name="seo_title" value="{{ old('seo_name', $course->seo_title) }}" placeholder="Browser tab title" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Meta Description <span class="text-red-500">*</span></label>
                            <textarea name="meta_description" rows="4" placeholder="Short meta description (150–160 characters recommended)..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors resize-none">{{ old('meta_description', $course->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Quill script library -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    const editors = {};

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

    // Initialize Quill for all 4 rich text fields
    document.addEventListener('DOMContentLoaded', function() {
        setupQuill('overview');
        setupQuill('course_objective');
        setupQuill('course_meterial_content');
        setupQuill('wsa');

        // Show price list if a tier is already selected (e.g. in edit mode)
        togglePriceList();

        // Initialize Select2
        if (typeof jQuery !== 'undefined') {
            $('#primary-category').select2({
                placeholder: "Select Category",
                allowClear: true,
                width: '100%'
            });
            $('#secondary-categories').select2({
                placeholder: "Select Secondary Categories",
                allowClear: true,
                width: '100%'
            });
            $('#course-accreditation').select2({
                placeholder: "Select Course Accreditation",
                allowClear: true,
                width: '100%'
            });
        }
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
            // Activate Source HTML view mode
            textareaEl.classList.add('active-source-mode');
            textareaEl.classList.remove('hidden');
            editorEl.classList.add('hidden');
            
            // Sync current rich text into raw html text area
            textareaEl.value = ql.root.innerHTML;
            
            // Highlight <> button with green background
            btn.classList.add('bg-[#008060]', 'text-white');
            btn.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
            
            // Disable other visual toolbar buttons
            toolbar.querySelectorAll('button:not(.custom-html-btn)').forEach(b => {
                b.disabled = true;
                b.style.opacity = '0.35';
                b.style.pointerEvents = 'none';
            });
        } else {
            // Deactivate Source HTML view mode
            textareaEl.classList.remove('active-source-mode');
            textareaEl.classList.add('hidden');
            editorEl.classList.remove('hidden');
            
            // Load edited HTML source back to rich text editor
            ql.root.innerHTML = textareaEl.value;
            
            // Update the hidden input
            const input = document.querySelector(`input[name="${id}"]`);
            if (input) input.value = textareaEl.value;

            // Reset <> button highlighting
            btn.classList.remove('bg-[#008060]', 'text-white');
            btn.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
            
            // Re-enable visual toolbar buttons
            toolbar.querySelectorAll('button:not(.custom-html-btn)').forEach(b => {
                b.disabled = false;
                b.style.opacity = '1';
                b.style.pointerEvents = 'auto';
            });
        }
    };



    function togglePriceList() {
        const select  = document.getElementById('price-tier-select');
        const card    = document.getElementById('price-list-card');
        const badge   = document.getElementById('selected-tier-badge');
        const tbody   = document.getElementById('price-list-body');
        const selected = select.value;

        if (selected) {
            const option = select.options[select.selectedIndex];
            const baseRate = parseFloat(option.getAttribute('data-base-rate')) || 0;
            const dailyRate = parseFloat(option.getAttribute('data-daily-rate')) || 0;
            
            // USD to GBP conversion rate (example logic: 1.24, wait, let's check the mockup. In mockup GBP 1,000 -> USD 1,240, so USD = GBP * 1.24)
            const exchangeRate = 1.24;
            const daysArr = [1, 3, 5, 10];
            let html = '';

            daysArr.forEach(days => {
                const priceGBP = (baseRate * Math.round(days / 5)) + (dailyRate * days);
                const priceUSD = priceGBP * exchangeRate;

                html += `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <td class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700">
                        <input type="number" readonly value="${baseRate}" class="w-20 text-xs text-center bg-gray-100 dark:bg-gray-600 border border-gray-200 dark:border-gray-500 text-gray-500 dark:text-gray-400 rounded px-2 py-1.5 focus:outline-none cursor-not-allowed">
                    </td>
                    <td class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700">
                        <input type="number" readonly value="${dailyRate}" class="w-20 text-xs text-center bg-gray-100 dark:bg-gray-600 border border-gray-200 dark:border-gray-500 text-gray-500 dark:text-gray-400 rounded px-2 py-1.5 focus:outline-none cursor-not-allowed">
                    </td>
                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-medium border-r border-gray-200 dark:border-gray-700">${days}</td>
                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold border-r border-gray-200 dark:border-gray-700">${priceGBP.toLocaleString()}</td>
                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold">${priceUSD.toLocaleString()}</td>
                </tr>
                `;
            });
            tbody.innerHTML = html;

            // Show the card with a smooth fade-in effect
            card.classList.remove('hidden');
            card.style.opacity = '0';
            card.style.transform = 'translateY(-6px)';
            requestAnimationFrame(() => {
                card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                card.style.opacity    = '1';
                card.style.transform  = 'translateY(0)';
            });

            // Update the badge label
            const label = option.text;
            badge.textContent = label;

        } else {
            // Hide the card
            card.style.opacity   = '0';
            card.style.transform = 'translateY(-6px)';
            setTimeout(() => card.classList.add('hidden'), 250);
            badge.textContent = '';
        }
    }
</script>
@endsection
