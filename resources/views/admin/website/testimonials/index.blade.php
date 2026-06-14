@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-gray-600 dark:text-gray-300">Website</span>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Testimonials</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage customer testimonials displayed on the website.</p>
        </div>
        <a href="/admin/website/testimonials/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">

        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                <select id="entries-per-page" onchange="changeEntries()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                <input type="text" id="testimonial-search" oninput="filterItems()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 w-44 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('author')">Author <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('description')">Description <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 text-center w-28">Actions</th>
                    </tr>
                </thead>
                <tbody id="testimonial-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>

        <div id="empty-state" class="hidden py-16 text-center">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No testimonials found</h3>
        </div>

        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <div class="flex items-center gap-2">
                <button type="button" onclick="prevPage()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">Page</span>
                <input type="number" id="page-input" value="1" min="1" onchange="goToPage(this.value)" class="w-12 text-center text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <span class="text-sm text-gray-600 dark:text-gray-400">of <span id="total-pages">1</span></span>
                <button type="button" onclick="nextPage()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="table-summary">
                Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> items
            </p>
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
    let items = @json($testimonials), filtered = [], currentPage = 1, itemsPerPage = 25, sortCol = '', sortAsc = true;

    document.addEventListener('DOMContentLoaded', () => {
        filterItems();
    });

    function truncate(str, n) { return str && str.length > n ? str.substring(0, n) + '...' : (str || ''); }

    function filterItems() {
        const q = document.getElementById('testimonial-search').value.toLowerCase().trim();
        filtered = items.filter(c => c.author.toLowerCase().includes(q) || c.description.toLowerCase().includes(q));
        if (sortCol) filtered.sort((a, b) => {
            let A = String(a[sortCol]).toLowerCase(), B = String(b[sortCol]).toLowerCase();
            return sortAsc ? A.localeCompare(B) : B.localeCompare(A);
        });
        currentPage = 1; document.getElementById('page-input').value = 1; renderTable();
    }

    function sortTable(col) { sortCol === col ? sortAsc = !sortAsc : (sortCol = col, sortAsc = true); filterItems(); }
    function clearSearch() { document.getElementById('testimonial-search').value = ''; filterItems(); }
    function changeEntries() { itemsPerPage = parseInt(document.getElementById('entries-per-page').value); currentPage = 1; renderTable(); }
    function prevPage() { if (currentPage > 1) { currentPage--; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function nextPage() { const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (currentPage < t) { currentPage++; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function goToPage(p) { p = parseInt(p); const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (p >= 1 && p <= t) { currentPage = p; renderTable(); } else { document.getElementById('page-input').value = currentPage; } }

    function renderTable() {
        const tbody = document.getElementById('testimonial-table-body');
        const empty = document.getElementById('empty-state');
        const summary = document.getElementById('table-summary');
        const totalPagesEl = document.getElementById('total-pages');
        tbody.innerHTML = '';
        if (filtered.length === 0) {
            tbody.classList.add('hidden'); empty.classList.remove('hidden');
            summary.innerHTML = 'Displaying <b>0</b> to <b>0</b> of <b>0</b> items';
            totalPagesEl.innerText = '1'; return;
        }
        tbody.classList.remove('hidden'); empty.classList.add('hidden');
        const total = filtered.length, totalPages = Math.ceil(total / itemsPerPage);
        totalPagesEl.innerText = totalPages;
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * itemsPerPage, end = Math.min(start + itemsPerPage, total);
        filtered.slice(start, end).forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300';
            
            tr.innerHTML = `
                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">${item.author}</td>
                <td class="px-5 py-3 text-[#008060] dark:text-emerald-400 max-w-lg">${truncate(item.description, 110)}</td>
                <td class="px-5 py-3 text-right">
                    <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                        <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                        </button>
                        <div class="kebab-menu hidden absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                            <button onclick="editItem(${item.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Edit
                            </button>
                            <button onclick="toggleStatus(${item.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs ${item.status === 'Active' ? 'text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20' : 'text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20'} transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                ${item.status === 'Active' ? 'Disable' : 'Enable'}
                            </button>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <button onclick="deleteItem(${item.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </td>`;
            tbody.appendChild(tr);
        });
        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start+1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function toggleStatus(id) {
        fetch(`/admin/website/testimonials/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = items.find(c => c.id === id);
                if (item) {
                    item.status = data.status;
                    filterItems();
                    showToast(data.status === 'Active' ? 'Testimonial enabled!' : 'Testimonial disabled!');
                }
            } else {
                alert('Failed to update status.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    }

    function deleteItem(id) {
        if (!confirm('Are you sure you want to delete this testimonial?')) return;

        fetch(`/admin/website/testimonials/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                items = items.filter(c => c.id !== id);
                filterItems();
                showToast('Testimonial deleted successfully!');
            } else {
                alert('Failed to delete testimonial.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    }

    function editItem(id) { window.location.href = `/admin/website/testimonials/${id}/edit`; }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3000);
    }

    function toggleKebab(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        if (!isOpen) menu.classList.remove('hidden');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.kebab-menu') && !e.target.closest('[onclick*="toggleKebab"]')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    });
</script>
@endsection
