@extends('admin.layout')

@section('content')
<div class="w-full">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/logs/{{ $slug }}" class="hover:text-gray-600 dark:hover:text-gray-300">Logs</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">{{ $logTitle }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logTitle }} &mdash; Search Log</h1>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Table Controls --}}
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
            <form id="filter-form" action="/admin/logs" method="GET" class="w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                    <select name="entries" onchange="document.getElementById('filter-form').submit()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                        <option value="10" {{ request('entries') == '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries', '100') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <input type="hidden" name="sort" value="{{ request('sort', 'created_dt') }}">
                    <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full md:w-72 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]"
                        placeholder="Search by name, email, IP…">
                    <button type="submit" class="hidden"></button>
                    <a href="/admin/logs" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">Clear filtering</a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        @php
                            $headers = [
                                'crm_ids' => 'CRM Ids',
                                'crm_update_dt' => 'CRM Update Dt',
                                'name' => 'Name',
                                'email' => 'Email',
                                'phone_no' => 'Phone No',
                                'ip' => 'IP',
                                'country' => 'Country'
                            ];
                        @endphp
                        @foreach($headers as $key => $label)
                        <th class="px-4 py-3 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'dir' => request('sort') == $key && request('dir') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1">
                                {{ $label }}
                                @if(request('sort') == $key)
                                    <span class="text-gray-600 dark:text-gray-300">{{ request('dir') == 'asc' ? '↑' : '↓' }}</span>
                                @else
                                    <span class="text-gray-400">↕</span>
                                @endif
                            </a>
                        </th>
                        @endforeach
                        <th class="px-4 py-3 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors whitespace-nowrap text-right text-gray-700 dark:text-gray-400 font-bold uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $index => $log)
                    <tr class="{{ $index % 2 === 0 ? '' : 'bg-blue-50/30 dark:bg-blue-900/5' }} hover:bg-gray-50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300">
                        <td class="px-4 py-3 text-gray-400 dark:text-gray-500">{{ $log->crm_ids }}</td>
                        <td class="px-4 py-3 text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $log->crm_update_dt }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $log->name }}</td>
                        <td class="px-4 py-3">{{ $log->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $log->phone_no }}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-mono">{{ $log->ip }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $log->country }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                                <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                </button>
                                <div class="kebab-menu hidden absolute right-0 mt-1 w-36 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                                    <button onclick="viewDetails(this)" data-log="{{ json_encode($log) }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <svg class="mx-auto w-10 h-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No log entries found</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search term.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span class="font-medium text-gray-900 dark:text-white">{{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900 dark:text-white">{{ $logs->total() }}</span> logs
            </p>
            <div class="flex gap-1">
                {{ $logs->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Background -->
<div id="details-modal" class="hidden fixed inset-0 z-[100] bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm overflow-y-auto" onclick="closeDetailsModal(event)">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <!-- This element is to trick the browser into centering the modal contents. -->
        <div class="inline-block w-full max-w-4xl p-6 my-8 text-left transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeDetailsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">Log Details</h3>
            
            <div id="details-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 text-sm max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar">
                <!-- Dynamically populated -->
            </div>
            
            <div class="mt-8 text-right border-t border-gray-200 dark:border-gray-700 pt-4">
                <button onclick="closeDetailsModal()" class="px-5 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold rounded-lg transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleKebab(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.kebab-menu').forEach(m => {
            m.classList.add('hidden');
            m.style.position = '';
            m.style.top = '';
            m.style.left = '';
        });
        
        if (!isOpen) {
            menu.classList.remove('hidden');
            const rect = btn.getBoundingClientRect();
            menu.style.position = 'fixed';
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.left = (rect.right - 144) + 'px'; // 144px is w-36
        }
    }
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.kebab-menu') && !e.target.closest('[onclick*="toggleKebab"]')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    // Hide dropdown on any scroll event to prevent floating menus
    document.addEventListener('scroll', function(e) {
        if (!e.target.closest('.kebab-menu')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    }, true);

    function viewDetails(btn) {
        // Hide the dropdown menu first
        document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        
        const logData = JSON.parse(btn.getAttribute('data-log'));
        const container = document.getElementById('details-content');
        container.innerHTML = '';
        
        // Define fields to show
        const fields = [
            { label: 'ID', key: 'id' },
            { label: 'Log Type', key: 'log_type' },
            { label: 'Created At', key: 'created_dt' },
            { label: 'Name', key: 'name' },
            { label: 'Email', key: 'email' },
            { label: 'Phone', key: 'phone_no' },
            { label: 'Company', key: 'company_name' },
            { label: 'Job Title', key: 'job_title' },
            { label: 'IP Address', key: 'ip' },
            { label: 'Country', key: 'country' },
            { label: 'Course Name', key: 'coursename' },
            { label: 'Course URL', key: 'course_url' },
            { label: 'Category', key: 'categoryname' },
            { label: 'Venue', key: 'coursevenue' },
            { label: 'Start Date', key: 'coursestartdt' },
            { label: 'Quantity', key: 'quantity' },
            { label: 'Price', key: 'price' },
            { label: 'Currency', key: 'currency' },
            { label: 'Coupon', key: 'coupon' },
            { label: 'Status', key: 'status' },
            { label: 'Finance Info', key: 'finance_info' },
            { label: 'Login ID', key: 'loginid' },
            { label: 'Login Username', key: 'loginusername' },
            { label: 'CRM IDs', key: 'crm_ids' },
            { label: 'CRM Update Dt', key: 'crm_update_dt' }
        ];
        
        fields.forEach(f => {
            const val = logData[f.key];
            if (val !== null && val !== undefined && val !== '') {
                const div = document.createElement('div');
                div.className = 'flex flex-col bg-gray-50 dark:bg-gray-700/30 p-3.5 rounded-lg border border-gray-100 dark:border-gray-700';
                div.innerHTML = `
                    <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400 mb-1.5">${f.label}</span>
                    <span class="font-medium text-gray-900 dark:text-gray-200 break-words whitespace-pre-wrap">${String(val).replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>
                `;
                container.appendChild(div);
            }
        });
        
        // Show modal and disable background scroll
        document.getElementById('details-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal(e) {
        if (e && e.target !== e.currentTarget) return;
        document.getElementById('details-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #CBD5E1;
    border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #475569;
}
</style>
@endsection
