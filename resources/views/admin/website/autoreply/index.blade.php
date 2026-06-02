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
                <span class="text-[#008060] font-extrabold">Email Autoreply</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Email Autoreply</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage automatic email responses for each website form.</p>
        </div>
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
                <input type="text" id="autoreply-search" oninput="filterItems()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('formName')">Form Name <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('mailSubject')">Mail Subject <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('mailContent')">Mail Content <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('defaultContent')">Default Content <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 text-center w-24">Actions</th>
                    </tr>
                </thead>
                <tbody id="autoreply-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>

        <div id="empty-state" class="hidden py-16 text-center">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No autoreplies found</h3>
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

<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Done!</span>
</div>

<script>
    const FORM_NAMES = ['Contact Us','Admin - Pay Later mail','Registration','Subscribe','Quote','Event','Enquire','Brochure','Course Registration','Certificate Validation','Pretraining','Outline Fail','Location PDF','Event Capture','Course Outline','Directory Document Download','Course Callus','Webinar Courses','Pay Later','Custom Payment'];

    const DEFAULT_ITEMS = [
        { id:1,  formName:'Contact Us',                  mailSubject:'Your support request has been received',      mailContent:'+44 20 7183 6657',           defaultContent:'Dear Test, Thank you for contacting...', mailPreview:'Thank you for your support request, you will shortly hear back from us.', status:'Active' },
        { id:2,  formName:'Admin - Pay Later mail',      mailSubject:'Pay later order details',                     mailContent:'[style*=Roboto] { font-family:...', defaultContent:'',                                  mailPreview:'', status:'Active' },
        { id:3,  formName:'Registration',                mailSubject:'Your registration request has been received', mailContent:'+44...',                     defaultContent:'Dear Test, Thank you for registering...', mailPreview:'', status:'Active' },
        { id:4,  formName:'Subscribe',                   mailSubject:"You're Subscribed!",                          mailContent:'+44...',                     defaultContent:'Dear Test, Thank you for signing...',    mailPreview:'', status:'Active' },
        { id:5,  formName:'Quote',                       mailSubject:'We have received your quotation request',     mailContent:'+44...',                     defaultContent:'Dear Test, Thank you for requesting...', mailPreview:'', status:'Active' },
        { id:6,  formName:'Event',                       mailSubject:'Your event registration request',             mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:7,  formName:'Enquire',                     mailSubject:'Your training course enquiry',                mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:8,  formName:'Brochure',                    mailSubject:'Your brochure is waiting for you',            mailContent:'+44...',                     defaultContent:'+44...',                                 mailPreview:'', status:'Active' },
        { id:9,  formName:'Course Registration',         mailSubject:'Your registration request',                   mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:10, formName:'Certificate Validation',      mailSubject:'Your certificate validation request',         mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:11, formName:'Pretraining',                 mailSubject:'We have received your Pre-Training request',  mailContent:'+44...',                     defaultContent:'Dear Trainee, Thank you for contacting...', mailPreview:'', status:'Active' },
        { id:12, formName:'Outline Fail',                mailSubject:'We have received your request',               mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:13, formName:'Location PDF',                mailSubject:'Your course joining instructions',            mailContent:'+44...',                     defaultContent:'Dear Trainee, Thank you for contacting...', mailPreview:'', status:'Active' },
        { id:14, formName:'Event Capture',               mailSubject:'Thank you for visiting us',                   mailContent:'+44...',                     defaultContent:'...',                                    mailPreview:'', status:'Active' },
        { id:15, formName:'Course Outline',              mailSubject:'Your course outline is ready',                mailContent:'+44...',                     defaultContent:'...',                                    mailPreview:'', status:'Active' },
        { id:16, formName:'Directory Document Download', mailSubject:'Your course outline is ready',                mailContent:'+44...',                     defaultContent:'...',                                    mailPreview:'', status:'Active' },
        { id:17, formName:'Course Callus',               mailSubject:'Your call back request has been received',    mailContent:'+44...',                     defaultContent:'...',                                    mailPreview:'', status:'Active' },
        { id:18, formName:'Outline Fail',                mailSubject:'Course Outline Limit Reached',                mailContent:'...',                        defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:19, formName:'Webinar Courses',             mailSubject:'Online training has never been easier',       mailContent:'+44...',                     defaultContent:'+44...',                                 mailPreview:'', status:'Active' },
        { id:20, formName:'Pay Later',                   mailSubject:'Your course registration request',            mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
        { id:21, formName:'Custom Payment',              mailSubject:'Thank you for your payment',                  mailContent:'+44...',                     defaultContent:'',                                       mailPreview:'', status:'Active' },
    ];

    let items = [], filtered = [], currentPage = 1, itemsPerPage = 25, sortCol = '', sortAsc = true;

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('londontfe_autoreply');
        if (saved) { try { items = JSON.parse(saved); } catch(e) {} }
        if (items.length === 0) { items = DEFAULT_ITEMS; save(); }
        filterItems();
    });

    function save() { localStorage.setItem('londontfe_autoreply', JSON.stringify(items)); }
    function truncate(str, n) { return str && str.length > n ? str.substring(0, n) + '...' : (str || '—'); }

    function filterItems() {
        const q = document.getElementById('autoreply-search').value.toLowerCase().trim();
        filtered = items.filter(c => c.formName.toLowerCase().includes(q) || c.mailSubject.toLowerCase().includes(q) || c.defaultContent.toLowerCase().includes(q));
        if (sortCol) filtered.sort((a, b) => { let A = String(a[sortCol]).toLowerCase(), B = String(b[sortCol]).toLowerCase(); return sortAsc ? A.localeCompare(B) : B.localeCompare(A); });
        currentPage = 1; document.getElementById('page-input').value = 1; renderTable();
    }

    function sortTable(col) { sortCol === col ? sortAsc = !sortAsc : (sortCol = col, sortAsc = true); filterItems(); }
    function clearSearch() { document.getElementById('autoreply-search').value = ''; filterItems(); }
    function changeEntries() { itemsPerPage = parseInt(document.getElementById('entries-per-page').value); currentPage = 1; renderTable(); }
    function prevPage() { if (currentPage > 1) { currentPage--; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function nextPage() { const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (currentPage < t) { currentPage++; document.getElementById('page-input').value = currentPage; renderTable(); } }
    function goToPage(p) { p = parseInt(p); const t = Math.ceil(filtered.length / itemsPerPage) || 1; if (p >= 1 && p <= t) { currentPage = p; renderTable(); } else { document.getElementById('page-input').value = currentPage; } }

    function renderTable() {
        const tbody = document.getElementById('autoreply-table-body');
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
                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">${item.formName}</td>
                <td class="px-5 py-3 text-[#008060] dark:text-emerald-400">${truncate(item.mailSubject, 48)}</td>
                <td class="px-5 py-3 text-gray-500 dark:text-gray-400">${truncate(item.mailContent, 32)}</td>
                <td class="px-5 py-3 text-[#008060] dark:text-emerald-400">${truncate(item.defaultContent, 38)}</td>
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
                        </div>
                    </div>
                </td>`;
            tbody.appendChild(tr);
        });
        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start+1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function editItem(id) { window.location.href = `/admin/website/autoreply/${id}/edit`; }

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
