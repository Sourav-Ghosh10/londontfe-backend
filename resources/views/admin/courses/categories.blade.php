@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Course Category</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage and organize your course categories and featured listings</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/courses/categories/create" class="flex items-center gap-1.5 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] px-4 py-2 rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Category
            </a>
        </div>
    </div>

    <!-- Filters and Actions Toolbar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            
            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status Filter</label>
                <div class="relative">
                    <select id="status-filter" onchange="filterCategories()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="all">All Categories</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search Categories</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="category-search" oninput="filterCategories()" placeholder="Search category by name..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
            </div>

        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/40 uppercase border-b border-gray-300 dark:border-gray-700 transition-colors">
                    <tr>
                        <th class="px-6 py-4">Category name</th>
                        <th class="px-6 py-4 text-center w-48">Featured category</th>
                        <th class="px-6 py-4 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody id="category-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    <!-- Loaded dynamically via Javascript for interactive demo -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No categories found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search terms.</p>
        </div>

        <!-- Table Footer/Pagination Summary -->
        <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 flex items-center justify-between transition-colors bg-gray-50 dark:bg-gray-900/10">
            <p class="text-sm text-gray-500 dark:text-gray-400" id="table-summary">
                Showing <span class="font-semibold text-gray-900 dark:text-white" id="displayed-count">0</span> of <span class="font-semibold text-gray-900 dark:text-white" id="total-count">0</span> categories
            </p>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Mock Management System
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Edit Category Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        
        <!-- Center element trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-300 dark:border-gray-700">
            <form onsubmit="handleEditCategory(event)">
                <input type="hidden" id="edit-category-id">
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="edit-modal-title">
                                Edit Category
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit-category-name" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Category Name</label>
                                    <input type="text" id="edit-category-name" required class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="e.g. Leadership and Management">
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="edit-category-featured" class="rounded border-gray-300 dark:border-gray-600 text-[#008060] focus:ring-[#008060] w-4.5 h-4.5 cursor-pointer">
                                    <label for="edit-category-featured" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none font-medium">Featured Category</label>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="edit-category-active" class="rounded border-gray-300 dark:border-gray-600 text-[#008060] focus:ring-[#008060] w-4.5 h-4.5 cursor-pointer">
                                    <label for="edit-category-active" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none font-medium">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#008060] text-base font-medium text-white hover:bg-[#006e52] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
        
        <!-- Center element trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="delete-modal-title">
                            Delete Category
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete <span id="delete-category-name-display" class="font-semibold text-gray-800 dark:text-gray-200"></span>? This action will remove the category from our listings. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="confirmDeleteCategory()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-250 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Premium Toast Notifications -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div id="toast-icon-wrapper" class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Action completed successfully!</span>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
    // Prepopulated Category Database based on your exact screenshot
    let categories = [
        { id: 1, name: "Innovation and Artificial Intelligence (AI)", featured: true, active: true, courseCount: 14 },
        { id: 2, name: "Leadership and Management", featured: true, active: true, courseCount: 32 },
        { id: 3, name: "Sales and Marketing", featured: false, active: true, courseCount: 18 },
        { id: 4, name: "Health, Safety and Environment", featured: false, active: true, courseCount: 9 },
        { id: 5, name: "Retail and E-Commerce", featured: false, active: true, courseCount: 11 },
        { id: 6, name: "Chemical Engineering", featured: false, active: true, courseCount: 7 },
        { id: 7, name: "Oil and Gas", featured: false, active: true, courseCount: 24 },
        { id: 8, name: "Strategy and Business Planning", featured: false, active: true, courseCount: 15 },
        { id: 9, name: "Quality and Productivity", featured: false, active: true, courseCount: 12 },
        { id: 10, name: "Procurement & Supply Chain Management", featured: false, active: true, courseCount: 21 },
        { id: 11, name: "Sustainability and CSR", featured: false, active: true, courseCount: 6 },
        { id: 12, name: "Energy and Sustainability", featured: false, active: true, courseCount: 13 },
        { id: 13, name: "Contract and Project Management", featured: false, active: true, courseCount: 19 },
        { id: 14, name: "Communications and Public Relations (PR)", featured: false, active: true, courseCount: 8 },
        { id: 15, name: "Industrial Manufacturing and Production", featured: true, active: true, courseCount: 16 },
        { id: 16, name: "Compliance and Legal", featured: true, active: true, courseCount: 10 },
        { id: 17, name: "Accounting and Finance", featured: true, active: true, courseCount: 27 },
        { id: 18, name: "Customer Experience and Relationship Management", featured: false, active: true, courseCount: 15 },
        { id: 19, name: "Human Resources and Talent Development", featured: true, active: true, courseCount: 22 },
        { id: 20, name: "Business Administration", featured: false, active: true, courseCount: 17 },
        // Dummy Inactive categories for status filters
        { id: 21, name: "Legacy Corporate Systems", featured: false, active: false, courseCount: 0 },
        { id: 22, name: "Traditional Marketing Methodologies", featured: false, active: false, courseCount: 0 }
    ];

    let deleteId = null;

    // Initialize Table
    document.addEventListener("DOMContentLoaded", () => {
        renderTable();
    });

    // Render Table Content
    function renderTable() {
        const tbody = document.getElementById("category-table-body");
        const emptyState = document.getElementById("empty-state");
        const searchVal = document.getElementById("category-search").value.toLowerCase().trim();
        const statusVal = document.getElementById("status-filter").value;

        // Filter database
        const filtered = categories.filter(cat => {
            const matchesSearch = cat.name.toLowerCase().includes(searchVal);
            let matchesStatus = true;
            if (statusVal === "active") matchesStatus = cat.active;
            else if (statusVal === "inactive") matchesStatus = !cat.active;
            return matchesSearch && matchesStatus;
        });

        // Clear Table
        tbody.innerHTML = "";

        if (filtered.length === 0) {
            emptyState.classList.remove("hidden");
        } else {
            emptyState.classList.add("hidden");
            filtered.forEach(cat => {
                const tr = document.createElement("tr");
                tr.className = "hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group";
                
                // Featured Switch Toggler (Premium iOS style)
                const featuredHtml = cat.featured
                    ? `<button onclick="toggleFeatured(${cat.id})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-[#008060] shadow-sm hover:opacity-90" aria-pressed="true" title="Currently Featured">
                           <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                       </button>`
                    : `<button onclick="toggleFeatured(${cat.id})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 shadow-sm" aria-pressed="false" title="Currently Standard">
                           <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                       </button>`;

                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="font-medium text-gray-950 dark:text-white text-sm md:text-[15px]">${cat.name}</span>
                            ${!cat.active ? '<span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xxs font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">Inactive</span>' : ''}
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            ${cat.courseCount} training courses
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        ${featuredHtml}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                            <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors focus:outline-none">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                            </button>
                            <div class="kebab-menu hidden absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                                <button onclick="openEditModal(${cat.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </button>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <button onclick="openDeleteModal(${cat.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Update counts
        document.getElementById("displayed-count").textContent = filtered.length;
        document.getElementById("total-count").textContent = categories.length;
    }

    // Trigger filters on input
    function filterCategories() {
        renderTable();
    }

    // Toggle Featured State directly in table
    function toggleFeatured(id) {
        const cat = categories.find(c => c.id === id);
        if (cat) {
            cat.featured = !cat.featured;
            renderTable();
            showToast(`${cat.name} featured status toggled!`, "success");
        }
    }

    // Modal Control: Edit Category
    function openEditModal(id) {
        const cat = categories.find(c => c.id === id);
        if (cat) {
            document.getElementById("edit-category-id").value = cat.id;
            document.getElementById("edit-category-name").value = cat.name;
            document.getElementById("edit-category-featured").checked = cat.featured;
            document.getElementById("edit-category-active").checked = cat.active;
            document.getElementById("edit-modal").classList.remove("hidden");
        }
    }

    function closeEditModal() {
        document.getElementById("edit-modal").classList.add("hidden");
    }

    function handleEditCategory(e) {
        e.preventDefault();
        const idVal = parseInt(document.getElementById("edit-category-id").value);
        const nameVal = document.getElementById("edit-category-name").value.trim();
        const featuredVal = document.getElementById("edit-category-featured").checked;
        const activeVal = document.getElementById("edit-category-active").checked;

        const cat = categories.find(c => c.id === idVal);
        if (cat && nameVal) {
            cat.name = nameVal;
            cat.featured = featuredVal;
            cat.active = activeVal;
            closeEditModal();
            renderTable();
            showToast(`Category updated successfully!`, "success");
        }
    }

    // Modal Control: Delete Category
    function openDeleteModal(id) {
        const cat = categories.find(c => c.id === id);
        if (cat) {
            deleteId = id;
            document.getElementById("delete-category-name-display").textContent = `"${cat.name}"`;
            document.getElementById("delete-modal").classList.remove("hidden");
        }
    }

    function closeDeleteModal() {
        document.getElementById("delete-modal").classList.add("hidden");
        deleteId = null;
    }

    function confirmDeleteCategory() {
        if (deleteId) {
            const index = categories.findIndex(c => c.id === deleteId);
            if (index !== -1) {
                const deletedName = categories[index].name;
                categories.splice(index, 1);
                closeDeleteModal();
                renderTable();
                showToast(`Category "${deletedName}" deleted!`, "error");
            }
        }
    }

    // Toast Control
    function showToast(message, type = "success") {
        const toast = document.getElementById("toast");
        const toastMsg = document.getElementById("toast-message");
        const toastIconWrapper = document.getElementById("toast-icon-wrapper");

        toastMsg.textContent = message;

        if (type === "success") {
            toastIconWrapper.className = "rounded-full p-1 bg-green-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            `;
        } else if (type === "error") {
            toastIconWrapper.className = "rounded-full p-1 bg-red-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
        }

        // Slide in from bottom
        toast.classList.remove("translate-y-24", "opacity-0");
        toast.classList.add("translate-y-0", "opacity-100");

        // Slide out after 3 seconds
        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
            toast.classList.remove("translate-y-0", "opacity-100");
        }, 3000);
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

<style>
    /* Styling extension to allow clean integration with dark/light themes */
    .dark .bg-gray-750 {
        background-color: #2a2e35;
    }
    .dark .text-gray-250 {
        color: #e2e8f0;
    }
    .text-xxs {
        font-size: 0.65rem;
    }
</style>
@endsection
