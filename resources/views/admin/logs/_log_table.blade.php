<div class="w-full">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/logs/quick-enquiry" class="hover:text-gray-600 dark:hover:text-gray-300">Logs</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">{{ $logTitle }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logTitle }} &mdash; Search Log</h1>
        </div>

        {{-- Export Button --}}
        <button onclick="exportCSV()" class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export
        </button>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Table Controls --}}
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                <select id="log-entries-per-page" onchange="changeEntriesPerPage()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                <input type="text" id="log-search" oninput="filterLogs()"
                    class="w-full md:w-72 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]"
                    placeholder="Search by name, email, IP…">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">Clear filtering</button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors whitespace-nowrap" onclick="sortLogs('crm_ids')">CRM Ids <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors whitespace-nowrap" onclick="sortLogs('crm_update_dt')">CRM Update Dt <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortLogs('name')">Name <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortLogs('email')">Email <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors whitespace-nowrap" onclick="sortLogs('phone')">Phone No <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortLogs('ip')">IP <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortLogs('country')">Country <span class="text-gray-400 ml-1">&#8693;</span></th>
                    </tr>
                </thead>
                <tbody id="log-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>

        {{-- Empty state --}}
        <div id="log-empty-state" class="hidden py-16 text-center">
            <svg class="mx-auto w-10 h-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No log entries found</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search term.</p>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <div class="flex items-center gap-2">
                <button type="button" onclick="prevPage()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">Page</span>
                <input type="number" id="log-page-input" value="1" min="1" onchange="goToPage(this.value)"
                    class="w-12 text-center text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <span class="text-sm text-gray-600 dark:text-gray-400">of <span id="log-total-pages">1</span></span>
                <button type="button" onclick="nextPage()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="log-table-summary">
                Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> to
                <span class="font-bold text-gray-900 dark:text-white">0</span> of
                <span class="font-bold text-gray-900 dark:text-white">0</span> items
            </p>
        </div>
    </div>
</div>

<script>
    const LOG_STORAGE_KEY = '{{ $storageKey }}';
    const LOG_TITLE       = '{{ $logTitle }}';

    // ── Sample data ───────────────────────────────────────────────────
    const sampleLogs = [
        { id:1,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'152.58.178.190', country:'India' },
        { id:2,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'152.58.178.190', country:'India' },
        { id:3,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'152.58.178.190', country:'India' },
        { id:4,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'106.192.88.76',  country:'India' },
        { id:5,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'106.192.88.76',  country:'India' },
        { id:6,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravg.hashtag@gmail.com', phone:'+447956525262', ip:'223.185.34.218', country:'India' },
        { id:7,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravg.hashtag@gmail.com', phone:'+447956525262', ip:'223.185.34.218', country:'India' },
        { id:8,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravg.hashtag@gmail.com', phone:'++447956525262',ip:'223.185.34.218', country:'India' },
        { id:9,  crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravg.hashtag@gmail.com', phone:'+447956759595', ip:'223.185.34.218', country:'India' },
        { id:10, crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'106.192.83.14',  country:'India' },
        { id:11, crm_ids:'', crm_update_dt:'', name:'Kassim Hantouli', email:'kassim@londontfe.com',      phone:'+4488888888',   ip:'217.33.25.156',  country:'UNITED KINGDOM' },
        { id:12, crm_ids:'', crm_update_dt:'', name:'Sourav Ghosh',    email:'souravghoshmgu1@gmail.com', phone:'+447568564565', ip:'223.184.138.189',country:'India' },
    ];

    let logs         = [];
    let filteredLogs = [];
    let currentPage  = 1;
    let itemsPerPage = 100;
    let sortCol      = '';
    let sortAsc      = true;

    // ── Init ─────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem(LOG_STORAGE_KEY);
        try { logs = saved ? JSON.parse(saved) : []; } catch(e) { logs = []; }
        if (logs.length === 0) { logs = sampleLogs; saveLogs(); }
        filterLogs();
    });

    function saveLogs() { localStorage.setItem(LOG_STORAGE_KEY, JSON.stringify(logs)); }

    // ── Search & Sort ─────────────────────────────────────────────────
    function filterLogs() {
        const q = document.getElementById('log-search').value.toLowerCase().trim();
        filteredLogs = logs.filter(r =>
            r.name.toLowerCase().includes(q)   ||
            r.email.toLowerCase().includes(q)  ||
            r.ip.toLowerCase().includes(q)     ||
            r.country.toLowerCase().includes(q)||
            r.phone.toLowerCase().includes(q)
        );
        if (sortCol) {
            filteredLogs.sort((a, b) => {
                const va = (a[sortCol] || '').toString().toLowerCase();
                const vb = (b[sortCol] || '').toString().toLowerCase();
                if (va < vb) return sortAsc ? -1 : 1;
                if (va > vb) return sortAsc ?  1 : -1;
                return 0;
            });
        }
        currentPage = 1;
        document.getElementById('log-page-input').value = 1;
        renderTable();
    }

    function sortLogs(col) {
        sortAsc = (sortCol === col) ? !sortAsc : true;
        sortCol = col;
        filterLogs();
    }

    function clearSearch() {
        document.getElementById('log-search').value = '';
        filterLogs();
    }

    // ── Pagination ────────────────────────────────────────────────────
    function changeEntriesPerPage() {
        itemsPerPage = parseInt(document.getElementById('log-entries-per-page').value);
        currentPage  = 1;
        document.getElementById('log-page-input').value = 1;
        renderTable();
    }

    function goToPage(page) {
        page = parseInt(page);
        const total = Math.ceil(filteredLogs.length / itemsPerPage) || 1;
        currentPage = Math.min(Math.max(page, 1), total);
        document.getElementById('log-page-input').value = currentPage;
        renderTable();
    }

    function prevPage() { if (currentPage > 1) goToPage(currentPage - 1); }
    function nextPage() { const t = Math.ceil(filteredLogs.length / itemsPerPage) || 1; if (currentPage < t) goToPage(currentPage + 1); }

    // ── Render ────────────────────────────────────────────────────────
    function renderTable() {
        const tbody   = document.getElementById('log-table-body');
        const empty   = document.getElementById('log-empty-state');
        const summary = document.getElementById('log-table-summary');
        const totEl   = document.getElementById('log-total-pages');
        tbody.innerHTML = '';

        if (filteredLogs.length === 0) {
            tbody.classList.add('hidden');
            empty.classList.remove('hidden');
            summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> items`;
            totEl.innerText = '1';
            return;
        }

        tbody.classList.remove('hidden');
        empty.classList.add('hidden');

        const total      = filteredLogs.length;
        const totalPages = Math.ceil(total / itemsPerPage);
        totEl.innerText  = totalPages;
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * itemsPerPage;
        const end   = Math.min(start + itemsPerPage, total);
        const page  = filteredLogs.slice(start, end);

        page.forEach((r, idx) => {
            const tr = document.createElement('tr');
            tr.className = `${idx % 2 === 0 ? '' : 'bg-blue-50/30 dark:bg-blue-900/5'} hover:bg-gray-50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300`;
            tr.innerHTML = `
                <td class="px-4 py-3 text-gray-400 dark:text-gray-500">${r.crm_ids || ''}</td>
                <td class="px-4 py-3 text-gray-400 dark:text-gray-500 whitespace-nowrap">${r.crm_update_dt || ''}</td>
                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200 whitespace-nowrap">${r.name}</td>
                <td class="px-4 py-3">${r.email}</td>
                <td class="px-4 py-3 whitespace-nowrap">${r.phone}</td>
                <td class="px-4 py-3 whitespace-nowrap font-mono">${r.ip}</td>
                <td class="px-4 py-3 whitespace-nowrap">${r.country}</td>
            `;
            tbody.appendChild(tr);
        });

        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start + 1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    // ── CSV Export ────────────────────────────────────────────────────
    function exportCSV() {
        const headers = ['CRM Ids','CRM Update Dt','Name','Email','Phone No','IP','Country'];
        const rows    = filteredLogs.map(r => [r.crm_ids, r.crm_update_dt, r.name, r.email, r.phone, r.ip, r.country]);
        const csv     = [headers, ...rows].map(r => r.map(v => `"${(v||'').toString().replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob    = new Blob([csv], { type: 'text/csv' });
        const a       = document.createElement('a');
        a.href        = URL.createObjectURL(blob);
        a.download    = `${LOG_TITLE.replace(/\s+/g,'-').toLowerCase()}-log.csv`;
        a.click();
    }
</script>
