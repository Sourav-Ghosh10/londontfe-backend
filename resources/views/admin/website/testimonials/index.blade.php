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
    const DEFAULT_TESTIMONIALS = [
        { id:1,  author:'Peter W.',     description:'Thank you for the opportunity for having attended Advanced Skills of the Bid and Tender Management Process...', authorInfo:'Senior Manager, UK', status:'Active' },
        { id:2,  author:'Ove H.',       description:'The course is very adaptable to everyday work and contributes to developing CSR framework in our own...', authorInfo:'Director, Norway', status:'Active' },
        { id:3,  author:'Bilal M.',     description:'The training provided by LondonTFE are very inspirational and thought provoking. The outlines are comprehensive and the...', authorInfo:'Manager, Saudi Arabia', status:'Active' },
        { id:4,  author:'Anna M.',      description:'I was learning everything about effective training administration, from training needs analysis to final evaluation. The trainer...', authorInfo:'Training Officer, UAE', status:'Active' },
        { id:5,  author:'Gerry B.',     description:'Our employees are always having interest on your offered public courses or programs. This is why...', authorInfo:'HR Director, Ireland', status:'Active' },
        { id:6,  author:'Margret J.',   description:'London Training for Excellence has given me a great opportunity and the style of teaching was...', authorInfo:'Administrator, Canada', status:'Active' },
        { id:7,  author:'Eng. Mazin A.',description:'London Training for Excellence has offered a wide selection of training courses that has allowed me...', authorInfo:'Engineer, Kuwait', status:'Active' },
        { id:8,  author:'Lusine H.',    description:'I received excellent service during all steps of training registration, payment process and the actual training...', authorInfo:'Specialist, Armenia', status:'Active' },
        { id:9,  author:'Nabil B.',     description:'I can certainly say that the quality of service I was offered with was very impressive...', authorInfo:'Consultant, Lebanon', status:'Active' },
        { id:10, author:'Ahoud A.',     description:'The training was very inspirational, bringing lots of ideas and learning from experience. I have gained...', authorInfo:'Analyst, Jordan', status:'Active' },
    ];

    let items = [], filtered = [], currentPage = 1, itemsPerPage = 25, sortCol = '', sortAsc = true;

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('londontfe_testimonials');
        if (saved) { try { items = JSON.parse(saved); } catch(e) {} }
        if (items.length === 0) { items = DEFAULT_TESTIMONIALS; save(); }
        filterItems();
    });

    function save() { localStorage.setItem('londontfe_testimonials', JSON.stringify(items)); }
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
            const disabledIcon = item.status === 'Active'
                ? `<button onclick="toggleStatus(${item.id})" title="Disable" class="text-red-500 hover:text-red-700 transition-colors p-1">
                       <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                   </button>`
                : `<button onclick="toggleStatus(${item.id})" title="Enable" class="text-gray-400 hover:text-green-600 transition-colors p-1">
                       <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                   </button>`;
            tr.innerHTML = `
                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">${item.author}</td>
                <td class="px-5 py-3 text-[#008060] dark:text-emerald-400 max-w-lg">${truncate(item.description, 110)}</td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        ${disabledIcon}
                        <button onclick="editItem(${item.id})" class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </div>
                </td>`;
            tbody.appendChild(tr);
        });
        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start+1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function toggleStatus(id) {
        const item = items.find(c => c.id === id);
        if (item) {
            item.status = item.status === 'Active' ? 'Inactive' : 'Active';
            save(); filterItems();
            showToast(item.status === 'Active' ? 'Testimonial enabled!' : 'Testimonial disabled!');
        }
    }

    function editItem(id) { window.location.href = `/admin/website/testimonials/${id}/edit`; }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3000);
    }
</script>
@endsection
