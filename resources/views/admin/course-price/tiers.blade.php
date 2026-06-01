@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-gray-600 dark:text-gray-300">Course Price</span>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Price Tier</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Price Tier</h1>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add Price Tier
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/80">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                <select id="entries-per-page" onchange="changeEntriesPerPage()" class="text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                <input type="text" id="tier-search" oninput="filterTiers()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear filtering</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('name')">Tier name <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('des')">Tier des <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('base')">Base Rate(GBP) <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('daily')">Daily Rate(GBP) <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('created')">Created at <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('updated')">Updated at <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="tier-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>
        
        <div id="empty-state" class="hidden py-16 text-center">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No items found</h3>
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

<!-- Add/Edit Modal -->
<div id="tier-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800/80">
                <h3 class="text-base font-bold text-gray-900 dark:text-white" id="modal-title">Add Price Tier</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form onsubmit="handleSave(event)" class="p-6 space-y-4">
                <input type="hidden" id="edit-id">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tier Name <span class="text-red-500">*</span></label>
                    <input type="text" id="tier-name" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors" placeholder="e.g. Test Tire">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tier Description</label>
                    <input type="text" id="tier-des" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors" placeholder="e.g. Test Tire 2">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Base Rate (GBP) <span class="text-red-500">*</span></label>
                    <input type="number" id="tier-base" step="0.01" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors" placeholder="e.g. 1100">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Daily Rate (GBP) <span class="text-red-500">*</span></label>
                    <input type="number" id="tier-daily" step="0.01" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors" placeholder="e.g. 1000">
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 dark:border-gray-650 text-sm font-semibold rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
                </div>
            </form>
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
    let tiers = [
        { id: 1, name: "Test Tire", des: "", base: "1100", daily: "1000", created: "08/01/2026 - 11:01", updated: "08/01/2026 - 11:01" },
        { id: 2, name: "Test", des: "", base: "1200", daily: "500", created: "09/01/2026 - 11:48", updated: "09/01/2026 - 11:48" },
        { id: 3, name: "Test Tire 2", des: "Test Tire 2", base: "1400", daily: "400", created: "11/01/2026 - 08:08", updated: "11/01/2026 - 08:08" }
    ];

    let currentPage = 1;
    let itemsPerPage = 100;
    let filteredTiers = [];
    let sortCol = '';
    let sortAsc = true;

    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem("londontfe_price_tiers");
        if (saved) { try { tiers = JSON.parse(saved); } catch(e) {} }
        filterTiers();
    });

    function saveTiers() { localStorage.setItem("londontfe_price_tiers", JSON.stringify(tiers)); }

    function filterTiers() {
        const search = document.getElementById("tier-search").value.toLowerCase().trim();
        filteredTiers = tiers.filter(t => 
            t.name.toLowerCase().includes(search) || 
            t.des.toLowerCase().includes(search) ||
            t.base.toString().includes(search) ||
            t.daily.toString().includes(search)
        );
        
        if (sortCol) {
            filteredTiers.sort((a, b) => {
                let valA = a[sortCol];
                let valB = b[sortCol];
                if (sortCol === 'base' || sortCol === 'daily') {
                    valA = parseFloat(valA) || 0;
                    valB = parseFloat(valB) || 0;
                } else {
                    valA = valA.toString().toLowerCase();
                    valB = valB.toString().toLowerCase();
                }
                if (valA < valB) return sortAsc ? -1 : 1;
                if (valA > valB) return sortAsc ? 1 : -1;
                return 0;
            });
        }
        
        currentPage = 1;
        document.getElementById("page-input").value = 1;
        renderTable();
    }
    
    function sortTable(col) {
        if (sortCol === col) {
            sortAsc = !sortAsc;
        } else {
            sortCol = col;
            sortAsc = true;
        }
        filterTiers();
    }

    function clearSearch() {
        document.getElementById("tier-search").value = "";
        filterTiers();
    }
    
    function changeEntriesPerPage() {
        itemsPerPage = parseInt(document.getElementById("entries-per-page").value);
        currentPage = 1;
        document.getElementById("page-input").value = 1;
        renderTable();
    }
    
    function goToPage(page) {
        page = parseInt(page);
        const totalPages = Math.ceil(filteredTiers.length / itemsPerPage) || 1;
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        } else {
            document.getElementById("page-input").value = currentPage;
        }
    }
    
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            document.getElementById("page-input").value = currentPage;
            renderTable();
        }
    }
    
    function nextPage() {
        const totalPages = Math.ceil(filteredTiers.length / itemsPerPage) || 1;
        if (currentPage < totalPages) {
            currentPage++;
            document.getElementById("page-input").value = currentPage;
            renderTable();
        }
    }

    function renderTable() {
        const tbody = document.getElementById("tier-table-body");
        const empty = document.getElementById("empty-state");
        const summary = document.getElementById("table-summary");
        const totalPagesEl = document.getElementById("total-pages");
        tbody.innerHTML = "";

        if (filteredTiers.length === 0) {
            tbody.classList.add("hidden");
            empty.classList.remove("hidden");
            summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> items`;
            totalPagesEl.innerText = "1";
            return;
        }

        tbody.classList.remove("hidden");
        empty.classList.add("hidden");

        const total = filteredTiers.length;
        const totalPages = Math.ceil(total / itemsPerPage);
        totalPagesEl.innerText = totalPages;
        
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, total);
        const page = filteredTiers.slice(start, end);

        page.forEach(item => {
            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";
            tr.innerHTML = `
                <td class="px-5 py-4">${item.name}</td>
                <td class="px-5 py-4">${item.des}</td>
                <td class="px-5 py-4">${item.base}</td>
                <td class="px-5 py-4">${item.daily}</td>
                <td class="px-5 py-4">${item.created}</td>
                <td class="px-5 py-4">${item.updated}</td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="deleteTier(${item.id})" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <button onclick="openEditModal(${item.id})" class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors p-1" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start + 1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add Price Tier";
        document.getElementById("edit-id").value = "";
        document.getElementById("tier-name").value = "";
        document.getElementById("tier-des").value = "";
        document.getElementById("tier-base").value = "";
        document.getElementById("tier-daily").value = "";
        document.getElementById("tier-modal").classList.remove("hidden");
    }

    function openEditModal(id) {
        const tier = tiers.find(t => t.id === id);
        if (!tier) return;
        document.getElementById("modal-title").innerText = "Edit Price Tier";
        document.getElementById("edit-id").value = id;
        document.getElementById("tier-name").value = tier.name;
        document.getElementById("tier-des").value = tier.des;
        document.getElementById("tier-base").value = tier.base;
        document.getElementById("tier-daily").value = tier.daily;
        document.getElementById("tier-modal").classList.remove("hidden");
    }

    function closeModal() { document.getElementById("tier-modal").classList.add("hidden"); }

    function handleSave(e) {
        e.preventDefault();
        const id = document.getElementById("edit-id").value;
        const name = document.getElementById("tier-name").value.trim();
        const des = document.getElementById("tier-des").value.trim();
        const base = document.getElementById("tier-base").value.trim();
        const daily = document.getElementById("tier-daily").value.trim();
        
        const now = new Date();
        const dateStr = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} - ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

        if (id) {
            const idx = tiers.findIndex(t => t.id == id);
            if (idx !== -1) { 
                tiers[idx].name = name; 
                tiers[idx].des = des; 
                tiers[idx].base = base; 
                tiers[idx].daily = daily;
                tiers[idx].updated = dateStr;
            }
        } else {
            const newId = tiers.length ? Math.max(...tiers.map(t => t.id)) + 1 : 1;
            tiers.push({ id: newId, name, des, base, daily, created: dateStr, updated: dateStr });
        }
        
        saveTiers();
        closeModal();
        filterTiers();
        showToast(id ? "Price Tier updated!" : "Price Tier added!");
    }

    function deleteTier(id) {
        if (confirm("Delete this Price Tier?")) {
            tiers = tiers.filter(t => t.id !== id);
            saveTiers();
            filterTiers();
            showToast("Price Tier deleted!");
        }
    }

    function showToast(msg) {
        const t = document.getElementById("toast");
        document.getElementById("toast-message").innerText = msg;
        t.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        setTimeout(() => { t.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm"; }, 3500);
    }
</script>
@endsection
