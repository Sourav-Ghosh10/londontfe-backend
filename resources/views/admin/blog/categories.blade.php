@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/blog" class="hover:text-gray-600 dark:hover:text-gray-300">Blog</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Categories</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Blog Categories</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage categories used to organise blog articles.</p>
        </div>
        <a href="/admin/blog/categories/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add Category
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xxs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4">Category Name</th>
                        <th class="px-5 py-4">Slug</th>
                        <th class="px-5 py-4">Articles</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="cat-table-body" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>
        <div id="empty-state" class="hidden py-16 text-center">
            <div class="p-3 bg-gray-50 dark:bg-gray-750 inline-flex rounded-full text-gray-400 mb-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No categories found</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Add your first category to get started.</p>
        </div>
        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex items-center justify-between bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="cat-summary">0 categories</p>
        </div>
    </div>

</div>

<!-- Add/Edit Modal -->
<div id="cat-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800/80">
                <h3 class="text-base font-bold text-gray-900 dark:text-white" id="modal-title">Add Category</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form onsubmit="handleSave(event)" class="p-6 space-y-4">
                <input type="hidden" id="edit-id">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" id="cat-name" required oninput="autoSlug()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors" placeholder="e.g. Project Management">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Slug</label>
                    <input type="text" id="cat-slug" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors font-mono" placeholder="auto-generated">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Status</label>
                    <div class="relative">
                        <select id="cat-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
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
    let categories = [
        { id: 1, name: "Project Management", slug: "project-management", articles: 3, status: "Active" },
        { id: 2, name: "Finance", slug: "finance", articles: 2, status: "Active" },
        { id: 3, name: "Leadership", slug: "leadership", articles: 2, status: "Active" },
        { id: 4, name: "Technology", slug: "technology", articles: 3, status: "Active" },
        { id: 5, name: "General", slug: "general", articles: 3, status: "Active" },
    ];

    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem("londontfe_blog_cats");
        if (saved) { try { categories = JSON.parse(saved); } catch(e) {} }
        renderTable();
    });

    function saveCats() { localStorage.setItem("londontfe_blog_cats", JSON.stringify(categories)); }

    function autoSlug() {
        const name = document.getElementById("cat-name").value;
        document.getElementById("cat-slug").value = name.toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");
    }

    function renderTable() {
        const tbody = document.getElementById("cat-table-body");
        const empty = document.getElementById("empty-state");
        tbody.innerHTML = "";

        if (categories.length === 0) {
            empty.classList.remove("hidden");
            document.getElementById("cat-summary").innerText = "0 categories";
            return;
        }
        empty.classList.add("hidden");
        document.getElementById("cat-summary").innerText = `${categories.length} categor${categories.length === 1 ? "y" : "ies"}`;

        categories.forEach(cat => {
            const statusBadge = cat.status === "Active"
                ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">Active</span>`
                : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">Inactive</span>`;

            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";
            tr.innerHTML = `
                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">${cat.name}</td>
                <td class="px-5 py-4 font-mono text-gray-500 dark:text-gray-400">${cat.slug}</td>
                <td class="px-5 py-4 font-mono text-gray-500">${cat.articles}</td>
                <td class="px-5 py-4 text-center">${statusBadge}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2.5">
                        <button onclick="openEditModal(${cat.id})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="deleteCategory(${cat.id})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add Category";
        document.getElementById("edit-id").value = "";
        document.getElementById("cat-name").value = "";
        document.getElementById("cat-slug").value = "";
        document.getElementById("cat-status").value = "Active";
        document.getElementById("cat-modal").classList.remove("hidden");
    }

    function openEditModal(id) {
        const cat = categories.find(c => c.id === id);
        if (!cat) return;
        document.getElementById("modal-title").innerText = "Edit Category";
        document.getElementById("edit-id").value = id;
        document.getElementById("cat-name").value = cat.name;
        document.getElementById("cat-slug").value = cat.slug;
        document.getElementById("cat-status").value = cat.status;
        document.getElementById("cat-modal").classList.remove("hidden");
    }

    function closeModal() { document.getElementById("cat-modal").classList.add("hidden"); }

    function handleSave(e) {
        e.preventDefault();
        const id = document.getElementById("edit-id").value;
        const name = document.getElementById("cat-name").value.trim();
        const slug = document.getElementById("cat-slug").value.trim() || name.toLowerCase().replace(/[^a-z0-9]+/g, "-");
        const status = document.getElementById("cat-status").value;

        if (id) {
            const idx = categories.findIndex(c => c.id == id);
            if (idx !== -1) { categories[idx].name = name; categories[idx].slug = slug; categories[idx].status = status; }
        } else {
            const newId = categories.length ? Math.max(...categories.map(c => c.id)) + 1 : 1;
            categories.push({ id: newId, name, slug, articles: 0, status });
        }
        saveCats();
        closeModal();
        renderTable();
        showToast(id ? "Category updated!" : "Category added!");
    }

    function deleteCategory(id) {
        if (confirm("Delete this category?")) {
            categories = categories.filter(c => c.id !== id);
            saveCats();
            renderTable();
            showToast("Category deleted!");
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
