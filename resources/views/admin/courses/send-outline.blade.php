@extends('admin.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single { height: 42px; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #f6f6f7; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 42px; color: #111827; padding-left: 0.75rem; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
    html.dark .select2-container .select2-selection--single { background-color: #374151; border-color: #4b5563; }
    html.dark .select2-container--default .select2-selection--single .select2-selection__rendered { color: #e5e7eb; }
    html.dark .select2-dropdown { background-color: #374151; border-color: #4b5563; color: #e5e7eb; }
    html.dark .select2-container--default .select2-results__option[aria-selected=true] { background-color: #4b5563; color: #fff; }
    html.dark .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #008060; color: white; }
    html.dark .select2-search input { background-color: #1f2937; color: #e5e7eb; border-color: #4b5563; }
</style>

<div class="max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Send Customer Outlines</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Send a course outline directly to a customer via email</p>
        </div>
    </div>

    {{-- Success / Error Alerts --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-lg mb-5 text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg mb-5 text-sm">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recipient & Course Details</h2>
        </div>

        <form method="POST" action="{{ route('admin.courses.send-outline.send') }}" class="p-6 space-y-5">
            @csrf

            {{-- Row: Title + First Name + Last Name --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <select name="title" id="title"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none truncate">
                        @foreach(['Mr','Mrs','Ms','Miss','Dr','Prof','Other'] as $t)
                        <option value="{{ $t }}" {{ old('title') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="John"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Smith"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
            </div>

            {{-- Row: Email + Phone --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="customer@example.com"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+44 7000 000000"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
            </div>

            {{-- Course Select --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Course <span class="text-red-500">*</span></label>
                <select name="course_id" id="course_id"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Currency --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Currency <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="currency" value="GBP" {{ old('currency', 'GBP') === 'GBP' ? 'checked' : '' }}
                            class="w-4 h-4 text-[#008060] border-gray-300 focus:ring-[#008060]">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">GBP</span>
                    </label>
                </div>
            </div>

            {{-- Venue Date + Venue + Price --}}
            <div x-data="{ isCustom: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Column 1: Date --}}
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 truncate">
                        <span x-text="isCustom ? 'Start date ' : 'Venue Date '"></span><span class="text-red-500">*</span>
                    </label>
                    <div x-show="!isCustom">
                        <select name="venue_date_id" id="venue_date_id"
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none truncate">
                            <option value="">-- Select Date --</option>
                        </select>
                    </div>
                    <div x-show="isCustom" style="display: none;">
                        <input type="date" name="custom_start_date"
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                    </div>
                </div>

                {{-- Column 2: Venue --}}
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 truncate">Venue <span class="text-red-500">*</span></label>
                    <div x-show="!isCustom">
                        <input type="text" name="venue" id="venue_display" readonly value="{{ old('venue', '') }}" placeholder="Auto-filled from date"
                            class="w-full text-sm bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md px-3 py-2 focus:outline-none transition-colors cursor-not-allowed truncate">
                        <input type="hidden" name="venue_id" id="venue_id">
                    </div>
                    <div x-show="isCustom" style="display: none;">
                        <select name="custom_venue_id"
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none truncate">
                            <option value="">-- Select Venue --</option>
                            @foreach($allVenues as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Column 3: Price --}}
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 truncate">Price <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0"
                            class="w-full min-w-0 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        
                        <button type="button" @click="isCustom = !isCustom; if(isCustom) { document.getElementById('price').value=''; document.getElementById('price').focus(); }"
                            class="flex-shrink-0 text-xs text-gray-500 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors whitespace-nowrap" x-text="isCustom ? 'Exit' : 'Custom'">
                        </button>
                        <input type="hidden" name="is_custom" :value="isCustom ? '1' : '0'">
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Footer Note</label>
                <textarea name="footer_note" id="footer_note" rows="3" placeholder="Optional message to include at the bottom of the outline..."
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none">{{ old('footer_note') }}</textarea>
            </div>

            {{-- Account No --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Account No.</label>
                <input type="text" name="account_no" id="account_no" value="{{ old('account_no') }}" placeholder="Optional account number"
                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" id="send-btn"
                    class="flex items-center gap-2 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send Outline
                </button>
                <a href="{{ route('admin.courses.index') }}"
                    class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 px-5 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // Initialize Select2 on Course dropdown for searchable
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#course_id').select2({
                placeholder: '-- Select Course --',
                allowClear: true,
                width: '100%',
            }).on('change', function () {
                loadDates(this.value);
            });
        } else {
            document.getElementById('course_id').addEventListener('change', function () {
                loadDates(this.value);
            });
        }
    });

    function loadDates(courseId) {
        const dateSelect   = document.getElementById('venue_date_id');
        const venueDisplay = document.getElementById('venue_display');
        const venueIdInput = document.getElementById('venue_id');
        const priceInput   = document.getElementById('price');

        // Reset
        dateSelect.innerHTML   = '<option value="">-- Select Date --</option>';
        venueDisplay.value     = '';
        venueIdInput.value     = '';
        priceInput.value       = '';

        if (!courseId) return;

        fetch(`/admin/courses/send-outline/dates?course_id=${courseId}`)
            .then(r => r.json())
            .then(dates => {
                dates.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value        = d.id;
                    opt.textContent  = d.start_date + ' — ' + d.venue_name;
                    opt.dataset.venue     = d.venue_name;
                    opt.dataset.venueId   = d.venue_id;
                    opt.dataset.price     = d.price;
                    dateSelect.appendChild(opt);
                });
            });
    }

    document.getElementById('venue_date_id').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('venue_display').value = opt.dataset.venue || '';
        document.getElementById('venue_id').value      = opt.dataset.venueId || '';
        document.getElementById('price').value         = opt.dataset.price || '';
    });
</script>
@endsection
