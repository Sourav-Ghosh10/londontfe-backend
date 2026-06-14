@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Users</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Staff Management</h1>
        </div>
        <a href="/admin/users/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add Staff
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        
        <form method="GET" action="/admin/users" id="filter-form">
            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'id') }}">
            <input type="hidden" name="sort_dir" id="sort_dir" value="{{ request('sort_dir', 'desc') }}">
            
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                    <select name="per_page" id="entries-per-page" onchange="this.form.submit()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" id="staff-search" value="{{ request('search') }}" class="w-full text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                        @if(request('search'))
                        <a href="/admin/users?per_page={{ request('per_page', 100) }}&sort_by={{ request('sort_by', 'id') }}&sort_dir={{ request('sort_dir', 'desc') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-450 hover:text-gray-655">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                        @endif
                    </div>
                    <a href="/admin/users" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">Clear filtering</a>
                </div>
            </div>
        </form>

        @php
            $sortBy = request('sort_by', 'id');
            $sortDir = request('sort_dir', 'desc');
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('name')">
                            Staff Name 
                            <span class="{{ $sortBy === 'name' ? 'text-[#008060] font-bold' : 'text-gray-400' }} ml-1">
                                {!! $sortBy === 'name' ? ($sortDir === 'asc' ? '↑' : '↓') : '&#8693;' !!}
                            </span>
                        </th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('email')">
                            Email 
                            <span class="{{ $sortBy === 'email' ? 'text-[#008060] font-bold' : 'text-gray-400' }} ml-1">
                                {!! $sortBy === 'email' ? ($sortDir === 'asc' ? '↑' : '↓') : '&#8693;' !!}
                            </span>
                        </th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('country')">
                            Country 
                            <span class="{{ $sortBy === 'country' ? 'text-[#008060] font-bold' : 'text-gray-400' }} ml-1">
                                {!! $sortBy === 'country' ? ($sortDir === 'asc' ? '↑' : '↓') : '&#8693;' !!}
                            </span>
                        </th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('type')">
                            Type 
                            <span class="{{ $sortBy === 'type' ? 'text-[#008060] font-bold' : 'text-gray-400' }} ml-1">
                                {!! $sortBy === 'type' ? ($sortDir === 'asc' ? '↑' : '↓') : '&#8693;' !!}
                            </span>
                        </th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-center" onclick="sortTable('is_admin_eligible')">
                            Admin Login 
                            <span class="{{ $sortBy === 'is_admin_eligible' ? 'text-[#008060] font-bold' : 'text-gray-400' }} ml-1">
                                {!! $sortBy === 'is_admin_eligible' ? ($sortDir === 'asc' ? '↑' : '↓') : '&#8693;' !!}
                            </span>
                        </th>
                        <th class="px-5 py-4 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300" data-id="{{ $item['id'] }}">
                            <td class="px-5 py-4 font-semibold">{{ $item['name'] }}</td>
                            <td class="px-5 py-4">{{ $item['email'] }}</td>
                            <td class="px-5 py-4">{{ $item['country'] }}</td>
                            <td class="px-5 py-4">{{ $item['type'] }}</td>
                            <td class="px-5 py-4 text-center font-semibold text-gray-900 dark:text-white">{{ $item['is_admin_eligible'] }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                                    <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                    </button>
                                    <div class="kebab-menu hidden absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                                        <a href="/admin/users/{{ $item['id'] }}/edit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            Edit
                                        </a>
                                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                        <button onclick="deleteStaff({{ $item['id'] }})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">No items found</h3>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                Displaying <span class="font-bold text-gray-900 dark:text-white">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-gray-900 dark:text-white">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ $users->total() }}</span> items
            </p>
            @if($users->hasPages())
            <nav class="inline-flex items-center gap-1.5" aria-label="Pagination">
                {{-- Previous --}}
                @if($users->onFirstPage())
                <span class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </span>
                @else
                <a href="{{ $users->previousPageUrl() }}" class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endif

                {{-- Page Numbers --}}
                @foreach($users->getUrlRange(max(1, $users->currentPage()-2), min($users->lastPage(), $users->currentPage()+2)) as $page => $url)
                    @if($page == $users->currentPage())
                    <span class="flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold bg-[#008060] text-white border-[#008060]">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else
                <span class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
                @endif
            </nav>
            @endif
        </div>
    </div>

</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Done!</span>
</div>

<script>
    // Search: debounce submit on input
    const searchInput = document.getElementById("staff-search");
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => searchInput.closest('form').submit(), 450);
        });
    }

    function sortTable(col) {
        const sortByInput = document.getElementById('sort_by');
        const sortDirInput = document.getElementById('sort_dir');
        
        let currentSort = sortByInput.value;
        let currentDir = sortDirInput.value;
        
        if (currentSort === col) {
            sortDirInput.value = currentDir === 'asc' ? 'desc' : 'asc';
        } else {
            sortByInput.value = col;
            sortDirInput.value = 'asc';
        }
        
        document.getElementById('filter-form').submit();
    }

    function deleteStaff(id) {
        if (!confirm("Are you sure you want to delete this staff member?")) return;

        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove row from DOM
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
                showToast("Staff member deleted successfully!");
            } else {
                alert("Failed to delete staff member.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("An error occurred.");
        });
    }
    
    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => {
            t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        }, 3000);
    }

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
            menu.style.left = (rect.right - 160) + 'px'; // 160px is w-40
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
</script>
@endsection
