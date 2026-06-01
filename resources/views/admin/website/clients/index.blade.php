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
                <span class="text-[#008060] font-extrabold">Our Clients</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Our Clients</h1>
        </div>
        <a href="/admin/website/clients/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add Client
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">

        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                <select id="entries-per-page" onchange="changeEntries()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                <input type="text" id="clients-search" oninput="filterClients()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('logo')">Client Logo <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('alt')">Alt Text <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('order')">Order <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('status')">Status <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 text-center w-28">Actions</th>
                    </tr>
                </thead>
                <tbody id="clients-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>

        <div id="empty-state" class="hidden py-16 text-center">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No clients found</h3>
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
    let clients = [], filtered = [], currentPage = 1, itemsPerPage = 100, sortCol = '', sortAsc = true;

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('londontfe_clients');
        if (saved) { try { clients = JSON.parse(saved); } catch(e) {} }
        if (clients.length === 0) {
            clients = [
                { id: 1, logo: '', alt: 'foundation wind energy icon', order: 1, status: 'Active' },
                { id: 2, logo: '', alt: 'petronas icon', order: 2, status: 'Active' },
                { id: 3, logo: '', alt: 'ministry of finance icon', order: 3, status: 'Active' },
                { id: 4, logo: '', alt: 'ministry of energy icon', order: 4, status: 'Active' },
                { id: 5, logo: '', alt: 'indonesia financial services authority', order: 5, status: 'Active' },
                { id: 6, logo: '', alt: 'federal mortgage bank of nigeria', order: 6, status: 'Active' },
                { id: 7, logo: '', alt: 'epexspot icon', order: 7, status: 'Active' },
                { id: 8, logo: '', alt: 'european central bank icon', order: 8, status: 'Active' },
            ];
            save();
        }
        filterClients();
    });

    function save() { localStorage.setItem('londontfe_clients', JSON.stringify(clients)); }

    function filterClients() {
        const q = document.getElementById('clients-search').value.toLowerCase().trim();
        filtered = clients.filter(c => c.alt.toLowerCase().includes(q) || c.status.toLowerCase().includes(q) || String(c.order).includes(q));
        if (sortCol) filtered.sort((a, b) => {
            let A = String(a[sortCol]).toLowerCase(), B = String(b[sortCol]).toLowerCase();
            return sortAsc ? A.localeCompare(B) : B.localeCompare(A);
        });
        currentPage = 1; document.getElementById('page-input').value = 1; renderTable();
    }

    function sortTable(col) { sortCol === col ? sortAsc = !sortAsc : (sortCol = col, sortAsc = true); filterClients(); }
    function clearSearch() { document.getElementById('clients-search').value = ''; filterClients(); }
    function changeEntries() { itemsPerPage = parseInt(document.getElementById('entries-per-page').value); currentPage = 1; renderTable(); }
    function prevPage() { if (currentPage > 1) { currentPage--; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function nextPage() { const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (currentPage < t) { currentPage++; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function goToPage(p) { p = parseInt(p); const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (p >= 1 && p <= t) { currentPage = p; renderTable(); } else { document.getElementById('page-input').value = currentPage; } }

    function renderTable() {
        const tbody = document.getElementById('clients-table-body');
        const empty = document.getElementById('empty-state');
        const summary = document.getElementById('table-summary');
        const totalPagesEl = document.getElementById('total-pages');
        tbody.innerHTML = '';
        if (filtered.length === 0) { tbody.classList.add('hidden'); empty.classList.remove('hidden'); summary.innerHTML = `Displaying <b>0</b> to <b>0</b> of <b>0</b> items`; totalPagesEl.innerText = '1'; return; }
        tbody.classList.remove('hidden'); empty.classList.add('hidden');
        const total = filtered.length, totalPages = Math.ceil(total / itemsPerPage);
        totalPagesEl.innerText = totalPages;
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * itemsPerPage, end = Math.min(start + itemsPerPage, total);
        filtered.slice(start, end).forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300';
            const logoCell = item.logo
                ? `<img src="${item.logo}" alt="${item.alt}" class="h-12 w-auto max-w-[100px] object-contain">`
                : `<div class="h-12 w-24 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-400 text-xxs">No Image</div>`;
            const statusSwitch = `
                <button type="button" onclick="toggleStatus(${item.id})" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#008060] focus:ring-offset-2 ${item.status === 'Active' ? 'bg-[#008060]' : 'bg-gray-200 dark:bg-gray-600'}">
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${item.status === 'Active' ? 'translate-x-4' : 'translate-x-0'}"></span>
                </button>
            `;
            tr.innerHTML = `
                <td class="px-5 py-3">${logoCell}</td>
                <td class="px-5 py-3">${item.alt}</td>
                <td class="px-5 py-3">${item.order}</td>
                <td class="px-5 py-3">${statusSwitch}</td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="deleteClient(${item.id})" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <button class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </div>
                </td>`;
            tbody.appendChild(tr);
        });
        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start+1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function toggleStatus(id) {
        const client = clients.find(c => c.id === id);
        if (client) {
            client.status = client.status === 'Active' ? 'Inactive' : 'Active';
            save();
            filterClients();
            const t = document.getElementById('toast'); document.getElementById('toast-message').innerText = 'Status updated!';
            t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
            setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 2000);
        }
    }

    function deleteClient(id) {
        if (confirm('Delete this client?')) {
            clients = clients.filter(c => c.id !== id); save(); filterClients();
            const t = document.getElementById('toast'); document.getElementById('toast-message').innerText = 'Client deleted!';
            t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
            setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3000);
        }
    }
</script>
@endsection
