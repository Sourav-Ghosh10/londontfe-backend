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
                <input type="text" id="staff-search" oninput="filterStaff()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear filtering</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('name')">Staff Name <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('email')">Email <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('country')">Country <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" onclick="sortTable('type')">Type <span class="text-gray-400 ml-1">&#8693;</span></th>
                        <th class="px-5 py-4 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
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

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Done!</span>
</div>

<script>
    let staff = [];
    let currentPage = 1;
    let itemsPerPage = 100;
    let filteredStaff = [];
    let sortCol = '';
    let sortAsc = true;

    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem("londontfe_staff");
        if (saved) { 
            try { staff = JSON.parse(saved); } catch(e) {} 
        }
        
        if (staff.length === 0) {
            staff = [
                { id: 1, name: "konstantinad7459", email: "konstantina@londontfe.com", country: "UNITED KINGDOM", type: "Marketing" },
                { id: 2, name: "isabelh778", email: "isabel@londontfe.com", country: "UNITED KINGDOM", type: "Sales" },
                { id: 3, name: "courseuser734", email: "operations@londontfe.com", country: "UNITED KINGDOM", type: "Course Editor" },
                { id: 4, name: "ayahm962", email: "ayah@londontfe.com", country: "UNITED KINGDOM", type: "Sales" },
                { id: 5, name: "johnb287", email: "john@londontfe.com", country: "UNITED KINGDOM", type: "Sales" },
                { id: 6, name: "Hibah297", email: "hiba@londontfe.com", country: "UNITED KINGDOM", type: "Course Editor" },
                { id: 7, name: "uksales665", email: "sales@londontfe.com", country: "UNITED KINGDOM", type: "Course Editor" },
                { id: 8, name: "Baraan579", email: "baraa@londontfe.com", country: "UNITED KINGDOM", type: "Operation" },
                { id: 9, name: "Shaymas831", email: "shayma@londontfe.com", country: "UNITED KINGDOM", type: "Sales" },
                { id: 10, name: "Sihamz883", email: "siham@londontfe.com", country: "UNITED KINGDOM", type: "Sales" },
                { id: 11, name: "Margiec491", email: "margie@londontfe.com", country: "UNITED KINGDOM", type: "Marketing" },
                { id: 12, name: "Abis734", email: "meta@surabhi.uk", country: "UNITED KINGDOM", type: "superadmin" },
                { id: 13, name: "abdelh741", email: "abdel@londontfe.com", country: "UNITED KINGDOM", type: "superadmin" },
                { id: 14, name: "kassimh852", email: "kassim@londontfe.com", country: "UNITED KINGDOM", type: "superadmin" }
            ];
            saveStaff();
        }
        
        filterStaff();
    });

    function saveStaff() { localStorage.setItem("londontfe_staff", JSON.stringify(staff)); }

    function filterStaff() {
        const search = document.getElementById("staff-search").value.toLowerCase().trim();
        filteredStaff = staff.filter(s => 
            s.name.toLowerCase().includes(search) || 
            s.email.toLowerCase().includes(search) ||
            s.country.toLowerCase().includes(search) ||
            s.type.toLowerCase().includes(search)
        );
        
        if (sortCol) {
            filteredStaff.sort((a, b) => {
                let valA = a[sortCol].toString().toLowerCase();
                let valB = b[sortCol].toString().toLowerCase();
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
        filterStaff();
    }

    function clearSearch() {
        document.getElementById("staff-search").value = "";
        filterStaff();
    }
    
    function changeEntriesPerPage() {
        itemsPerPage = parseInt(document.getElementById("entries-per-page").value);
        currentPage = 1;
        document.getElementById("page-input").value = 1;
        renderTable();
    }
    
    function goToPage(page) {
        page = parseInt(page);
        const totalPages = Math.ceil(filteredStaff.length / itemsPerPage) || 1;
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
        const totalPages = Math.ceil(filteredStaff.length / itemsPerPage) || 1;
        if (currentPage < totalPages) {
            currentPage++;
            document.getElementById("page-input").value = currentPage;
            renderTable();
        }
    }

    function renderTable() {
        const tbody = document.getElementById("staff-table-body");
        const empty = document.getElementById("empty-state");
        const summary = document.getElementById("table-summary");
        const totalPagesEl = document.getElementById("total-pages");
        tbody.innerHTML = "";

        if (filteredStaff.length === 0) {
            tbody.classList.add("hidden");
            empty.classList.remove("hidden");
            summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> items`;
            totalPagesEl.innerText = "1";
            return;
        }

        tbody.classList.remove("hidden");
        empty.classList.add("hidden");

        const total = filteredStaff.length;
        const totalPages = Math.ceil(total / itemsPerPage);
        totalPagesEl.innerText = totalPages;
        
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, total);
        const page = filteredStaff.slice(start, end);

        page.forEach(item => {
            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";
            
            tr.innerHTML = `
                <td class="px-5 py-4">${item.name}</td>
                <td class="px-5 py-4">${item.email}</td>
                <td class="px-5 py-4">${item.country}</td>
                <td class="px-5 py-4">${item.type}</td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="deleteStaff(${item.id})" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <button class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors p-1" title="History">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${start + 1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> items`;
    }

    function deleteStaff(id) {
        if (confirm("Delete this staff member?")) {
            staff = staff.filter(s => s.id !== id);
            saveStaff();
            filterStaff();
            showToast("Staff deleted!");
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
