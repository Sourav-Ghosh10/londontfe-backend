@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sub Venue Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage sub-venues, specific hotels, conference halls, and centers associated with primary course venues</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/courses/subvenues/create" class="flex items-center gap-1.5 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] px-4 py-2 rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Add Sub Venue
            </a>
        </div>
    </div>

    <!-- Filters and Actions Toolbar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            
            <!-- Parent Venue Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Primary Venue Filter</label>
                <div class="relative">
                    <select id="parent-venue-filter" onchange="filterSubVenues()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                        <option value="all">All Primary Venues</option>
                        <option value="London">London</option>
                        <option value="Dubai">Dubai</option>
                        <option value="Vienna">Vienna</option>
                        <option value="Athens">Athens</option>
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
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search Sub Venues</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="subvenue-search" oninput="filterSubVenues()" placeholder="Search by sub-venue name, hotel, or address..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
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
                        <th class="px-6 py-4">Sub venue name</th>
                        <th class="px-6 py-4">Primary venue</th>
                        <th class="px-6 py-4">Address / Details</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody id="subvenues-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    <!-- Loaded dynamically via Javascript for interactive demo -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No sub-venues found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search terms.</p>
        </div>

        <!-- Table Footer/Pagination Summary -->
        <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 flex items-center justify-between transition-colors bg-gray-50 dark:bg-gray-900/10">
            <p class="text-sm text-gray-500 dark:text-gray-400" id="table-summary">
                Showing <span class="font-semibold text-gray-900 dark:text-white" id="displayed-count">0</span> of <span class="font-semibold text-gray-900 dark:text-white" id="total-count">0</span> sub-venues
            </p>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Mock Sub Venue Management System
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Edit Sub Venue Modal -->
<div id="edit-subvenue-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="edit-subvenue-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeEditSubVenueModal()"></div>
        
        <!-- Center element trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-300 dark:border-gray-700">
            <form onsubmit="handleEditSubVenue(event)">
                <input type="hidden" id="edit-subvenue-id">
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="edit-subvenue-title">
                                Edit Sub Venue
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit-subvenue-name" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Sub Venue Name</label>
                                    <input type="text" id="edit-subvenue-name" required class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="e.g. London TFE Training Center">
                                </div>
                                <div>
                                    <label for="edit-subvenue-parent" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Primary Venue</label>
                                    <select id="edit-subvenue-parent" class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                                        <option value="London">London</option>
                                        <option value="Dubai">Dubai</option>
                                        <option value="Vienna">Vienna</option>
                                        <option value="Athens">Athens</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="edit-subvenue-address" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Address / Details</label>
                                    <input type="text" id="edit-subvenue-address" required class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="e.g. Kensington High St, London W8">
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="edit-subvenue-status" class="rounded border-gray-300 dark:border-gray-600 text-[#008060] focus:ring-[#008060] w-4.5 h-4.5 cursor-pointer">
                                    <label for="edit-subvenue-status" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none font-medium">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#008060] text-base font-medium text-white hover:bg-[#006e52] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditSubVenueModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-250 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-subvenue-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="delete-subvenue-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeDeleteSubVenueModal()"></div>
        
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
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="delete-subvenue-title">
                            Delete Sub Venue
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete <span id="delete-subvenue-name-display" class="font-semibold text-gray-800 dark:text-gray-250"></span>? This will permanently remove the sub-venue listing. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="confirmDeleteSubVenue()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
                <button type="button" onclick="closeDeleteSubVenueModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-250 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
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
    // Prepopulated Sub-Venue Database
    let subvenues = [
        { id: 1, name: "London TFE Training Center", parentVenue: "London", address: "Kensington High St, London W8 5SA", active: true },
        { id: 2, name: "Kensington Conference Palace", parentVenue: "London", address: "47 Gloucester Rd, South Kensington, London SW7 4PL", active: true },
        { id: 3, name: "Dubai Marina Heights Center", parentVenue: "Dubai", address: "Marina Heights, Tower A, Dubai Marina", active: true },
        { id: 4, name: "Jumeirah Beach Executive Suites", parentVenue: "Dubai", address: "Jumeirah Beach Road, Al Sufouh 1, Dubai", active: true },
        { id: 5, name: "Vienna Palace Suites", parentVenue: "Vienna", address: "Schönbrunner Schloßstraße 47, 1130 Wien", active: true },
        { id: 6, name: "Athens Presidential Hall", parentVenue: "Athens", address: "Syntagma Square, Athina 105 63", active: true }
    ];

    let deleteId = null;

    // Initialize Table
    document.addEventListener("DOMContentLoaded", () => {
        renderTable();
    });

    // Render Table Content
    function renderTable() {
        const tbody = document.getElementById("subvenues-table-body");
        const emptyState = document.getElementById("empty-state");
        const searchVal = document.getElementById("subvenue-search").value.toLowerCase().trim();
        const parentVal = document.getElementById("parent-venue-filter").value;

        // Filter database
        const filtered = subvenues.filter(sv => {
            const matchesSearch = sv.name.toLowerCase().includes(searchVal) || sv.address.toLowerCase().includes(searchVal);
            
            let matchesParent = true;
            if (parentVal !== "all") {
                matchesParent = sv.parentVenue === parentVal;
            }

            return matchesSearch && matchesParent;
        });

        // Clear Table
        tbody.innerHTML = "";

        if (filtered.length === 0) {
            emptyState.classList.remove("hidden");
        } else {
            emptyState.classList.add("hidden");
            filtered.forEach(sv => {
                const tr = document.createElement("tr");
                tr.className = "hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group text-gray-900 dark:text-gray-250";

                // Toggle Switch Status
                const statusHtml = sv.active
                    ? `<button onclick="toggleStatus(${sv.id})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-[#008060] shadow-sm hover:opacity-90" aria-pressed="true" title="Currently Active">
                           <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                       </button>`
                    : `<button onclick="toggleStatus(${sv.id})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 shadow-sm" aria-pressed="false" title="Currently Inactive">
                           <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                       </button>`;

                tr.innerHTML = `
                    <td class="px-6 py-4 font-semibold text-gray-950 dark:text-white text-[15px]">${sv.name}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                            ${sv.parentVenue}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">${sv.address}</td>
                    <td class="px-6 py-4 text-center">${statusHtml}</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2.5 opacity-90 group-hover:opacity-100 transition-opacity">
                            <button onclick="openEditSubVenueModal(${sv.id})" title="Edit Sub Venue" class="p-1.5 text-gray-400 hover:text-[#008060] hover:bg-emerald-55 dark:hover:bg-emerald-950/20 rounded-full border border-gray-250 dark:border-gray-600 transition-colors focus:outline-none shadow-xs bg-white dark:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button onclick="openDeleteSubVenueModal(${sv.id})" title="Delete Sub Venue" class="p-1.5 text-gray-400 hover:text-red-650 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-full border border-gray-250 dark:border-gray-600 transition-colors focus:outline-none shadow-xs bg-white dark:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Update counts
        document.getElementById("displayed-count").textContent = filtered.length;
        document.getElementById("total-count").textContent = subvenues.length;
    }

    // Trigger filters on input
    function filterSubVenues() {
        renderTable();
    }

    // Toggle active status
    function toggleStatus(id) {
        const sv = subvenues.find(item => item.id === id);
        if (sv) {
            sv.active = !sv.active;
            renderTable();
            showToast(`${sv.name} status toggled!`, "success");
        }
    }

    // Modal Control: Edit Sub Venue
    function openEditSubVenueModal(id) {
        const sv = subvenues.find(item => item.id === id);
        if (sv) {
            document.getElementById("edit-subvenue-id").value = sv.id;
            document.getElementById("edit-subvenue-name").value = sv.name;
            document.getElementById("edit-subvenue-parent").value = sv.parentVenue;
            document.getElementById("edit-subvenue-address").value = sv.address;
            document.getElementById("edit-subvenue-status").checked = sv.active;
            document.getElementById("edit-subvenue-modal").classList.remove("hidden");
        }
    }

    function closeEditSubVenueModal() {
        document.getElementById("edit-subvenue-modal").classList.add("hidden");
    }

    function handleEditSubVenue(e) {
        e.preventDefault();
        const idVal = parseInt(document.getElementById("edit-subvenue-id").value);
        const nameVal = document.getElementById("edit-subvenue-name").value.trim();
        const parentVal = document.getElementById("edit-subvenue-parent").value;
        const addressVal = document.getElementById("edit-subvenue-address").value.trim();
        const statusVal = document.getElementById("edit-subvenue-status").checked;

        const sv = subvenues.find(item => item.id === idVal);
        if (sv && nameVal && addressVal) {
            sv.name = nameVal;
            sv.parentVenue = parentVal;
            sv.address = addressVal;
            sv.active = statusVal;
            closeEditSubVenueModal();
            renderTable();
            showToast(`Sub venue updated successfully!`, "success");
        }
    }

    // Modal Control: Delete Sub Venue
    function openDeleteSubVenueModal(id) {
        const sv = subvenues.find(item => item.id === id);
        if (sv) {
            deleteId = id;
            document.getElementById("delete-subvenue-name-display").textContent = `"${sv.name}"`;
            document.getElementById("delete-subvenue-modal").classList.remove("hidden");
        }
    }

    function closeDeleteSubVenueModal() {
        document.getElementById("delete-subvenue-modal").classList.add("hidden");
        deleteId = null;
    }

    function confirmDeleteSubVenue() {
        if (deleteId) {
            const index = subvenues.findIndex(item => item.id === deleteId);
            if (index !== -1) {
                const deletedName = subvenues[index].name;
                subvenues.splice(index, 1);
                closeDeleteSubVenueModal();
                renderTable();
                showToast(`Sub venue "${deletedName}" deleted!`, "error");
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

        // Slide in
        toast.classList.remove("translate-y-24", "opacity-0");
        toast.classList.add("translate-y-0", "opacity-100");

        // Slide out
        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
            toast.classList.remove("translate-y-0", "opacity-100");
        }, 3000);
    }
</script>

<style>
    /* Sleek custom styling rules */
    .dark .bg-gray-750 {
        background-color: #2a2e35;
    }
    .dark .text-gray-250 {
        color: #e2e8f0;
    }
</style>
@endsection
