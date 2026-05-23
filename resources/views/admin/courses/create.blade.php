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
</style>

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

    <form action="#" method="POST">
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- ── LEFT COLUMN ─────────────────────────────────────── -->
            <div class="flex-1 space-y-5">

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    </div>
                    <div class="p-5 space-y-4">

                        <!-- Training Type -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Training Type</label>
                            <select class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="">Select training type</option>
                                <option>Classroom</option>
                                <option>Online</option>
                                <option>Blended</option>
                                <option>On-site / In-house</option>
                            </select>
                        </div>

                        <!-- Accreditation -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Accreditation at</label>
                            <input type="text" placeholder="e.g. CMI Recognised" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Course Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Course Name <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="e.g. Advanced Leadership Programme" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Price Tier -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Price Tier</label>
                            <select id="price-tier-select" onchange="togglePriceList()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="">Select Price Tier</option>
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                                <option value="enterprise">Enterprise</option>
                                <option value="custom">Custom</option>
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
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach([[1100,1000,1,1000,1240],[1100,1000,3,4100,5084],[1100,1000,5,6100,7564],[1100,1000,10,12200,15128]] as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700">
                                        <input type="number" value="{{ $row[0] }}" class="w-20 text-xs text-center bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700">
                                        <input type="number" value="{{ $row[1] }}" class="w-20 text-xs text-center bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-medium border-r border-gray-200 dark:border-gray-700">{{ $row[2] }}</td>
                                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold border-r border-gray-200 dark:border-gray-700">{{ number_format($row[3]) }}</td>
                                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold">{{ number_format($row[4]) }}</td>
                                </tr>
                                @endforeach
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
                        <div id="editor-overview" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                        <textarea id="source-overview" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                        <input type="hidden" name="overview">
                    </div>
                </div>

                <!-- Objective -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Objective <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-objective" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
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
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('objective')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-objective" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                        <textarea id="source-objective" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                        <input type="hidden" name="objective">
                    </div>
                </div>

                <!-- Week One -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Week One</h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-week-one" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
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
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('week_one')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-week-one" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                        <textarea id="source-week-one" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                        <input type="hidden" name="week_one">
                    </div>
                </div>

                <!-- Who Should Attend -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Who Should Attend</h2>
                    </div>
                    <div class="p-5 relative">
                        <div id="toolbar-who-attend" class="flex flex-wrap gap-1 p-2 bg-[#f6f6f7] dark:bg-gray-700/50 rounded-t-md border border-gray-200 dark:border-gray-600 border-b-0">
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
                            <button type="button" class="custom-html-btn p-1.5 w-7 h-7 rounded hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center font-mono font-bold text-xs text-gray-600 dark:text-gray-400" onclick="toggleQuillSourceCode('who_attend')">
                                &lt;&gt;
                            </button>
                        </div>
                        <div id="editor-who-attend" class="min-h-[160px] text-sm text-gray-800 dark:text-gray-200 bg-[#f6f6f7] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-b-md p-3"></div>
                        <textarea id="source-who-attend" class="hidden w-full min-h-[160px] font-mono text-xs bg-gray-900 text-green-400 border border-gray-800 p-3 rounded-b-md focus:outline-none"></textarea>
                        <input type="hidden" name="who_attend">
                    </div>
                </div>

                <!-- SEO & Meta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">SEO &amp; Meta</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Helps search engines index this course correctly</p>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tab Title</label>
                            <input type="text" placeholder="Browser tab title" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Meta Description <span class="text-red-500">*</span></label>
                            <textarea rows="4" placeholder="Short meta description (150–160 characters recommended)..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors resize-none"></textarea>
                        </div>
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

                <!-- Primary Category (sidebar) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm transition-colors">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Primary Category <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="p-4">
                        <div class="bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 max-h-52 overflow-y-auto">
                            @foreach(['Accounting and Finance','Administration and Office Management','Business Administration','Leadership and Management','Contract and Project Management','Energy and Sustainability','Oil and Gas','Sales and Marketing','Human Resources'] as $cat)
                            <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-white dark:hover:bg-gray-600 cursor-pointer transition-colors">
                                <input type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-xs text-gray-700 dark:text-gray-300">{{ $cat }}</span>
                            </label>
                            @endforeach
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
                                <input type="number" placeholder="5" min="1" class="w-16 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                                <select class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-2 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                    <option>Select duration type</option>
                                    <option>Days</option>
                                    <option>Weeks</option>
                                    <option>Hours</option>
                                    <option>Months</option>
                                </select>
                            </div>
                        </div>

                        <!-- CPD Hours -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">CPD Hours</label>
                            <input type="number" placeholder="e.g. 35" min="0" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                        <!-- Publish to XML -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Publish to XML</label>
                            <select class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <!-- Is Certified -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Is Certified</label>
                            <select class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option>Limited to condition</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>

                        <!-- Course Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Course Status <span class="text-red-500">*</span></label>
                            <select class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors appearance-none">
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Draft</option>
                            </select>
                        </div>

                        <!-- Course Certificate -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Course Certificate</label>
                            <input type="text" placeholder="e.g. GIBLA" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Quill script library -->
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
        setupQuill('objective');
        setupQuill('week_one');
        setupQuill('who_attend');
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
        const selected = select.value;

        if (selected) {
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
            const labels = {
                standard:   'Standard Tier',
                premium:    'Premium Tier',
                enterprise: 'Enterprise Tier',
                custom:     'Custom Tier',
            };
            badge.textContent = labels[selected] || selected;

            // Scroll smoothly to the price list card
            setTimeout(() => card.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
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
