@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Blog</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Blog Management</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage all published and draft blog articles.</p>
        </div>
        <div>
            <a href="/admin/blog/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
                + New Article
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                <div class="relative">
                    <select id="status-filter" onchange="filterArticles()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                        <option value="all">All Status</option>
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                        <option value="Pending">Pending Approval</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Category</label>
                <div class="relative">
                    <select id="cat-filter" onchange="filterArticles()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                        <option value="all">All Categories</option>
                        <option value="Project Management">Project Management</option>
                        <option value="Finance">Finance</option>
                        <option value="Leadership">Leadership</option>
                        <option value="Technology">Technology</option>
                        <option value="General">General</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-450 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35"/></svg>
                    </span>
                    <input type="text" id="blog-search" oninput="filterArticles()" placeholder="Search by title or author..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xxs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4">Title</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Author</th>
                        <th class="px-5 py-4">Post Date</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="blog-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden py-16 text-center">
            <div class="p-3 bg-gray-50 dark:bg-gray-750 inline-flex rounded-full text-gray-400 mb-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No articles found</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try adjusting your filters or search term.</p>
        </div>

        <!-- Footer Pagination -->
        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="table-summary">
                Showing <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> entries
            </p>
            <nav class="inline-flex items-center gap-1.5" id="pagination-controls" aria-label="Pagination"></nav>
        </div>
    </div>

</div>

<!-- Delete Confirm Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-sm w-full border border-gray-300 dark:border-gray-700 z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Delete Article</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-5">Are you sure you want to delete <span id="delete-title" class="font-semibold"></span>?</p>
            <div class="flex gap-3 justify-end">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 cursor-pointer">Cancel</button>
                <button onclick="confirmDelete()" class="px-4 py-2 text-sm font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 cursor-pointer">Delete</button>
            </div>
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
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredArticles = [];
    let deleteTarget = null;

    let articles = [
        { id: 1, title: "3 Innovations Chemical Engineers Should Know", category: "Technology", author: "Admin", date: "04/01/2026", status: "Published" },
        { id: 2, title: "Why Do You Need a PMI Certificate?", category: "Project Management", author: "Admin", date: "02/02/2026", status: "Published" },
        { id: 3, title: "The Implementation of Artificial Intelligence", category: "Technology", author: "Admin", date: "07/08/2023", status: "Published" },
        { id: 4, title: "A Decade in Numbers", category: "General", author: "Admin", date: "27/04/2023", status: "Published" },
        { id: 5, title: "Top Tips to Increase Job Satisfaction", category: "Leadership", author: "Admin", date: "04/05/2023", status: "Published" },
        { id: 6, title: "New Artificial Intelligence and...", category: "Technology", author: "Admin", date: "11/05/2023", status: "Published" },
        { id: 7, title: "The World is Flat – International Business", category: "General", author: "Admin", date: "20/07/2023", status: "Published" },
        { id: 8, title: "Top Tips to Improve Communication", category: "Leadership", author: "Admin", date: "18/08/2023", status: "Published" },
        { id: 9, title: "Step by Step Guide to Preparing Financial Reports", category: "Finance", author: "Admin", date: "25/05/2023", status: "Draft" },
        { id: 10, title: "Top 5 Project Management Challenges", category: "Project Management", author: "Admin", date: "01/06/2023", status: "Pending" },
        { id: 11, title: "Unlock the Excellence in Customer Service", category: "General", author: "Admin", date: "08/06/2023", status: "Published" },
        { id: 12, title: "Mastering Financial Accounting Fundamentals", category: "Finance", author: "Admin", date: "12/09/2023", status: "Draft" },
    ];

    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem("londontfe_blog_articles");
        if (saved) { try { articles = JSON.parse(saved); } catch(e) {} }
        filterArticles();
    });

    function saveArticles() {
        localStorage.setItem("londontfe_blog_articles", JSON.stringify(articles));
    }

    function filterArticles() {
        const status = document.getElementById("status-filter").value;
        const cat = document.getElementById("cat-filter").value;
        const search = document.getElementById("blog-search").value.toLowerCase().trim();
        currentPage = 1;

        let data = articles;
        if (status !== "all") data = data.filter(a => a.status === status);
        if (cat !== "all") data = data.filter(a => a.category === cat);
        if (search) data = data.filter(a => a.title.toLowerCase().includes(search) || a.author.toLowerCase().includes(search));

        renderTable(data);
    }

    function renderTable(data) {
        filteredArticles = data;
        const tbody = document.getElementById("blog-table-body");
        const empty = document.getElementById("empty-state");
        const summary = document.getElementById("table-summary");
        tbody.innerHTML = "";

        if (data.length === 0) {
            tbody.classList.add("hidden");
            empty.classList.remove("hidden");
            summary.innerHTML = `Showing <span class="font-bold text-gray-900 dark:text-white">0</span> to <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> entries`;
            renderPagination(0);
            return;
        }

        tbody.classList.remove("hidden");
        empty.classList.add("hidden");

        const total = data.length;
        const totalPages = Math.ceil(total / itemsPerPage);
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, total);
        const page = data.slice(start, end);

        page.forEach(item => {
            const statusBadge = {
                Published: `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">Published</span>`,
                Draft: `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">Draft</span>`,
                Pending: `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400">Pending</span>`,
            }[item.status] || "";

            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";
            tr.innerHTML = `
                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white max-w-xs truncate" title="${item.title}">${item.title}</td>
                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">${item.category}</td>
                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">${item.author}</td>
                <td class="px-5 py-4 font-mono text-gray-500">${item.date}</td>
                <td class="px-5 py-4 text-center">${statusBadge}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2.5">
                        <a href="/admin/blog/create?id=${item.id}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <button onclick="openDeleteModal(${item.id}, '${item.title.replace(/'/g, "\\'")}')" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        summary.innerHTML = `Showing <span class="font-bold text-gray-900 dark:text-white">${start + 1}</span> to <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> entries`;
        renderPagination(total);
    }

    function renderPagination(total) {
        const controls = document.getElementById("pagination-controls");
        controls.innerHTML = "";
        if (total === 0) return;
        const totalPages = Math.ceil(total / itemsPerPage);

        const prevBtn = document.createElement("button");
        prevBtn.type = "button";
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderTable(filteredArticles); } };
        prevBtn.className = `flex items-center justify-center p-2 rounded-md border text-xs font-semibold transition-colors cursor-pointer ${currentPage === 1 ? "bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed" : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"}`;
        prevBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>`;
        controls.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.onclick = () => { currentPage = i; renderTable(filteredArticles); };
            btn.className = `flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold transition-colors cursor-pointer ${currentPage === i ? "bg-[#008060] text-white border-[#008060]" : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"}`;
            btn.innerText = i;
            controls.appendChild(btn);
        }

        const nextBtn = document.createElement("button");
        nextBtn.type = "button";
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderTable(filteredArticles); } };
        nextBtn.className = `flex items-center justify-center p-2 rounded-md border text-xs font-semibold transition-colors cursor-pointer ${currentPage === totalPages ? "bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed" : "bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700"}`;
        nextBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>`;
        controls.appendChild(nextBtn);
    }

    function openDeleteModal(id, title) {
        deleteTarget = id;
        document.getElementById("delete-title").innerText = `"${title}"`;
        document.getElementById("delete-modal").classList.remove("hidden");
    }

    function closeDeleteModal() {
        document.getElementById("delete-modal").classList.add("hidden");
        deleteTarget = null;
    }

    function confirmDelete() {
        if (deleteTarget !== null) {
            articles = articles.filter(a => a.id !== deleteTarget);
            saveArticles();
            closeDeleteModal();
            filterArticles();
            showToast("Article deleted successfully!");
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
